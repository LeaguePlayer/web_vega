<?php
Yii::import('application.extensions.vendor.SimpleXMLReader.library.SimpleXMLReader');

/*
 * Общие требования для работы парсера:
 *  - поле перчичного ключа для всех сущностей должно называться id
 *  - все сущности должны содержать поля update_time, create_time ( в формате DATETIME )
 */



class VegaXMLParser extends SimpleXMLReader
{
    const MODE_INSERT_OR_UPDATE = 0;
    const MODE_REMOVE = 1;

    protected $_mode = self::MODE_INSERT_OR_UPDATE;
    protected $_db;
    protected $_cashe = array();

    public function __construct()
    {
        $this->registerCallback('UnloadedData', array($this, 'start_parse'), XMLREADER::ELEMENT);
        $this->registerCallback('UnloadedData', array($this, 'finish_parse'), XMLREADER::END_ELEMENT);

        $this->registerCallback('AddOrUpdateData', array($this, 'switch_mode_to_update'), XMLREADER::ELEMENT);
        $this->registerCallback('RemoveData', array($this, 'switch_mode_to_remove'), XMLREADER::ELEMENT);

        $this->registerCallback('Partners', array($this, 'start_partners'), XMLREADER::ELEMENT);
        $this->registerCallback('Partners', array($this, 'finish_partners'), XMLREADER::END_ELEMENT);
        $this->registerCallback('Partner', array($this, 'parse_partner'), XMLREADER::ELEMENT);

        $this->registerCallback('ProductsHierarchy', array($this, 'start_categories'), XMLREADER::ELEMENT);
        $this->registerCallback('ProductsHierarchy', array($this, 'finish_categories'), XMLREADER::END_ELEMENT);

        $this->registerCallback('Produtcts', array($this, 'start_products'), XMLREADER::ELEMENT);
        $this->registerCallback('Produtcts', array($this, 'finish_products'), XMLREADER::END_ELEMENT);
        $this->registerCallback('Product', array($this, 'parse_product'), XMLREADER::ELEMENT);

        $this->_db = Yii::app()->db;
    }


    protected function isNew($id, $table)
    {
        if ( !array_key_exists($table, $this->_cashe) ) {
            $exists = $this->_db->createCommand()->select('id')->from($table)->queryAll();
            $this->_cashe[$table] = CHtml::listData($exists, 'id', 'id');
        }

        return !array_key_exists($id, $this->_cashe[$table]);
    }


    protected function update($table, $columns)
    {
        $id = $columns['id'];
        $date = date('Y-m-d H:i:s');

        if ( $id && !$this->isNew($id, $table) ) {
            $columns['update_time'] = $date;
            $this->_db->createCommand()->update($table, $columns, 'id=:id', array(':id' => $id));
        } else {
            $columns['create_time'] = $columns['update_time'] = $date;
            if ($this->_db->createCommand()->insert($table, $columns) ) {
                $inserted_id = $this->_db->getLastInsertId();
                $this->_cashe[$table][$inserted_id] = $inserted_id;
            }
        }
    }


    // Открывающий тэг <UnloadedData>
    protected function start_parse($reader)
    {
        return true;
    }


    // Закрывающий тэг </UnloadedData>
    protected function finish_parse($reader)
    {
        return true;
    }


    // Встретился открывающий тэг <AddOrUpdateData>
    protected function switch_mode_to_update($reader)
    {
        $this->_mode = self::MODE_INSERT_OR_UPDATE;;
        return true;
    }


    // Встретился открывающий тэг <RemoveData>
    protected function switch_mode_to_remove($reader)
    {
        $this->_mode = self::MODE_REMOVE;
        return true;
    }




    /* =========
     * Партнеры
     * ========= */
    // Открывающий тэг <Partners>
    protected function start_partners($reader)
    {
        return true;
    }


    // Функция обработки партнеров
    protected function parse_partner($reader)
    {
        $xml = $reader->expandSimpleXml();
        $attributes = $xml->attributes();
        $columns = array(
            'id' => (string)$attributes['GUID'],
            'name' => (string)$attributes['Name'],
            'full_name' => (string)$attributes['FullName'],
            'inn' => (string)$attributes['INN'],
            'kpp' => (string)$attributes['KPP']
        );
        $this->update('{{partners}}', $columns);
        return true;
    }


    // Закрывающий тэг </Partners>
    protected function finish_partners($reader)
    {
        unset($this->_cashe['{{partners}}']);
        return true;
    }




    /* =========
     * Категории
     * ========= */
    // Открывающий тэг <ProductsHierarchy>
    protected function start_categories($reader)
    {
        $xml = $reader->expandSimpleXml();
        $roots_xml_categories = $xml->children();
        foreach ( $roots_xml_categories as $xml_category ) {
            $this->parse_category($xml_category);
        }
        return true;
    }


    // Рекурсивная функция обработки категорий
    protected function parse_category($xml_current, $parent_id = null, $current_level = 1)
    {
        $attributes = $xml_current->attributes();
        $columns = array(
            'id' => (string)$attributes['GUID'],
            'name' => (string)$attributes['Name'],
            'is_brand' => ( (string)$attributes['IsBrand'] == 'true'),
            'parent_id' => $parent_id,
            'level' => $current_level
        );
        $columns['translit_name'] = SiteHelper::translit( $columns['name'] );
        $this->update('{{categories}}', $columns);

        $children = $xml_current->children();
        foreach ( $children as $xml_child ) {
            $this->parse_category($xml_child, $columns['id'], $current_level + 1);
        }
    }


    // Закрывающий тэг </ProductsHierarchy>
    protected function finish_categories($reader)
    {
        unset($this->_cashe['{{categories}}']);
        return true;
    }




    /* =========
     * Товары
     * ========= */
    // Открывающий тэг <Produtcts>
    protected function start_products($reader)
    {
        return true;
    }


    // Открывающий тэг <Product>
    protected function parse_product($reader)
    {
        $xml = $reader->expandSimpleXml();
        $attributes = $xml->attributes();
        $columns = array(
            'id' => (string)$attributes['GUID'],
            'name' => (string)$attributes['Name'],
            'full_name' => (string)$attributes['FullName'],
            'category_id' => (string)$attributes['ParentGUID'],
            'article' => (string)$attributes['Article'],
            'description' => (string)$attributes['Description'],
        );
        $columns['translit_name'] = SiteHelper::translit( $columns['name'] );
        $this->update('{{products}}', $columns);
        return true;
    }


    // Закрывающий тэг </Produtcts>
    protected function finish_products($reader)
    {
        unset($this->_cashe['{{products}}']);
        return true;
    }
}
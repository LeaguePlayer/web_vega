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
    protected $_inserted = 0;
    protected $_updated = 0;
    protected $_removed = 0;
	protected $_pricetypes = array();



    public function parse(&$stat_info = array())
    {
        parent::parse();
        $stat_info['inserted'] = $this->_inserted;
        $stat_info['updated'] = $this->_updated;
        $stat_info['removed'] = $this->_removed;
        $this->_inserted = 0;
        $this->_updated = 0;
        $this->_removed = 0;
    }


    public function __construct()
    {
        $this->registerCallback('UnloadedData', array($this, 'start_parse'), XMLREADER::ELEMENT);
        $this->registerCallback('UnloadedData', array($this, 'finish_parse'), XMLREADER::END_ELEMENT);

        $this->registerCallback('AddOrUpdateData', array($this, 'switch_mode_to_update'), XMLREADER::ELEMENT);
        $this->registerCallback('RemoveData', array($this, 'switch_mode_to_remove'), XMLREADER::ELEMENT);
        $this->registerCallback('RemoveData', array($this, 'finish_remove_block'), XMLREADER::END_ELEMENT);

        $this->registerCallback('Partners', array($this, 'start_partners'), XMLREADER::ELEMENT);
        $this->registerCallback('Partners', array($this, 'finish_partners'), XMLREADER::END_ELEMENT);
        $this->registerCallback('Partner', array($this, 'parse_partner'), XMLREADER::ELEMENT);

		$this->registerCallback('Characteristics', array($this, 'start_characteristics'), XMLREADER::ELEMENT);
		$this->registerCallback('Characteristics', array($this, 'finish_characteristics'), XMLREADER::END_ELEMENT);
		$this->registerCallback('Characteristic', array($this, 'parse_characteristic'), XMLREADER::ELEMENT);

        $this->registerCallback('ProductsHierarchy', array($this, 'start_categories'), XMLREADER::ELEMENT);
        $this->registerCallback('ProductsHierarchy', array($this, 'finish_categories'), XMLREADER::END_ELEMENT);

        $this->registerCallback('Produtcts', array($this, 'start_products'), XMLREADER::ELEMENT);
        $this->registerCallback('Produtcts', array($this, 'finish_products'), XMLREADER::END_ELEMENT);
        $this->registerCallback('Product', array($this, 'parse_product'), XMLREADER::ELEMENT);

		$this->registerCallback('PriceType', array($this, 'parse_pricetype'), XMLREADER::ELEMENT);

		$this->registerCallback('Prices', array($this, 'start_prices'), XMLREADER::ELEMENT);
		$this->registerCallback('Prices', array($this, 'finish_prices'), XMLREADER::END_ELEMENT);
		$this->registerCallback('Price', array($this, 'parse_price'), XMLREADER::ELEMENT);

        $this->_db = Yii::app()->db;
    }


	protected function refreshCache($table)
	{
		$exists = $this->_db->createCommand()->select('id, guid')->from($table)->queryAll();
		$this->_cashe[$table] = CHtml::listData($exists, 'guid', 'id');
	}


    protected function isNew($guid, $table)
    {
        if ( !array_key_exists($table, $this->_cashe) ) {
			$this->refreshCache($table);
        }
        return !array_key_exists($guid, $this->_cashe[$table]);
    }


    protected function update($table, $columns)
    {
		$id = 0;
        $guid = $columns['guid'];
        $date = date('Y-m-d H:i:s');

        if ( $guid && !$this->isNew($guid, $table) ) {
			$id = $this->_cashe[$table][$guid];
            $columns['update_time'] = $date;
            if ( $this->_db->createCommand()->update($table, $columns, 'id=:id', array(':id' => $id)) ) {
                $this->_updated++;
            }
        } else {
            $columns['create_time'] = $columns['update_time'] = $date;
            if ($this->_db->createCommand()->insert($table, $columns) ) {
                $id = $this->_db->getLastInsertId();
                $this->_cashe[$table][$guid] = $id;
                $this->_inserted++;
            }
        }
		return $id;
    }


	protected function remove($table, $condition, $params)
	{
		return $this->_db->createCommand()->delete($table, $condition, $params);
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
		$this->_cashe = array();
        $this->_mode = self::MODE_INSERT_OR_UPDATE;
        return true;
    }


    // Встретился открывающий тэг <RemoveData>
    protected function switch_mode_to_remove($reader)
    {
        $this->_mode = self::MODE_REMOVE;
		$this->refreshCache('{{products}}');
		$this->refreshCache('{{partners}}');
		$this->refreshCache('{{characteristics}}');
        return true;
    }



	// Встретился закрывающий тэг </RemoveData>
	protected function finish_remove_block($reader)
	{
		unset($this->_cashe);
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

		if ( $this->_mode === self::MODE_INSERT_OR_UPDATE ) {
			$columns = array(
				'guid' => (string)$attributes['GUID'],
				'name' => (string)$attributes['Name'],
				'full_name' => (string)$attributes['FullName'],
				'inn' => (string)$attributes['INN'],
				'kpp' => (string)$attributes['KPP']
			);
			$this->update('{{partners}}', $columns);
		} else if ( $this->_mode === self::MODE_REMOVE ) {
			$params = array(
				':guid' => (string)$attributes['GUID'],
			);
			$this->_removed += $this->remove('{{partners}}', 'guid=:guid', $params);
		}
		return true;
    }


    // Закрывающий тэг </Partners>
    protected function finish_partners($reader)
    {
        unset($this->_cashe['{{partners}}']);
        return true;
    }




	/* =========
     * Характеристики товаров
     * ========= */
	// Открывающий тэг <Characteristics>
	protected function start_characteristics($reader)
	{
		return true;
	}


	// Функция обработки характеристик
	protected function parse_characteristic($reader)
	{
		$xml = $reader->expandSimpleXml();
		$attributes = $xml->attributes();

		if ( $this->_mode === self::MODE_INSERT_OR_UPDATE ) {
			$columns = array(
				'guid' => (string)$attributes['GUID'],
				'name' => (string)$attributes['Name'],
				'full_name' => (string)$attributes['FullName'],
			);
			$this->update('{{characteristics}}', $columns);
		} else if ( $this->_mode === self::MODE_REMOVE ) {
			$params = array(
				':guid' => (string)$attributes['GUID'],
			);
			$this->_removed += $this->remove('{{characteristics}}', 'guid=:guid', $params);
		}
		return true;
	}


	// Закрывающий тэг </Characteristics>
	protected function finish_characteristics($reader)
	{
		unset($this->_cashe['{{characteristics}}']);
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
            'guid' => (string)$attributes['GUID'],
            'name' => (string)$attributes['Name'],
            'is_brand' => ( (string)$attributes['IsBrand'] == 'true'),
            'parent_id' => $parent_id,
            'level' => $current_level
        );
        $columns['translit_name'] = SiteHelper::translit( $columns['name'] );
        $id = $this->update('{{categories}}', $columns);

        $children = $xml_current->children();
        foreach ( $children as $xml_child ) {
            $this->parse_category($xml_child, $id, $current_level + 1);
        }
    }


    // Закрывающий тэг </ProductsHierarchy>
    protected function finish_categories($reader)
    {
		// Пока не очищаем кэш категории, очистим после обработки товаров
        // unset($this->_cashe['{{categories}}']);
        return true;
    }




    /* =========
     * Товары
     * ========= */
    // Открывающий тэг <Produtcts>
    protected function start_products($reader)
    {
		// Проверяем, есть ли откуда брать id категорий
		if ( !array_key_exists('{{categories}}', $this->_cashe) ) {
			$this->refreshCache('{{categories}}');
		}
        return true;
    }


    // Открывающий тэг <Product>
    protected function parse_product($reader)
    {
        $xml = $reader->expandSimpleXml();
        $attributes = $xml->attributes();

		if ( $this->_mode === self::MODE_INSERT_OR_UPDATE ) {
			$columns = array(
				'guid' => (string)$attributes['GUID'],
				'name' => (string)$attributes['Name'],
				'full_name' => (string)$attributes['FullName'],
				'category_id' => $this->_cashe['{{categories}}'][(string)$attributes['ParentGUID']],
				'article' => (string)$attributes['Article'],
				'description' => (string)$attributes['Description'],
			);
			$columns['translit_name'] = SiteHelper::translit( $columns['name'] );
			$this->update('{{products}}', $columns);
		} else if ( $this->_mode === self::MODE_REMOVE ) {
			$params = array(
				':guid' => (string)$attributes['GUID'],
			);
			// Удаление связанных атрибутов товаров
			$command = $this->_db->createCommand("DELETE `v` FROM `{{product_attribute_values}}` `v`  LEFT JOIN `{{products}}` `p` ON `v`.`product_id`=`p`.`id` WHERE `p`.`guid` = :guid");
			$command->bindParam(':guid', $params[':guid'], PDO::PARAM_STR);
			$command->execute();
			$this->_removed += $this->remove('{{products}}', 'guid=:guid', $params);
		}
        return true;
    }


    // Закрывающий тэг </Produtcts>
    protected function finish_products($reader)
    {
		unset($this->_cashe['{{categories}}']);
//        unset($this->_cashe['{{products}}']);
        return true;
    }


	// Типы цен
	//  - розничная
	//  - оптовая
	protected function parse_pricetype($reader)
	{
		$xml = $reader->expandSimpleXml();
		$attributes = $xml->attributes();

		$guid = (string)$attributes['GUID'];
		$wholesale = (string)$attributes['Wholesale'];
		$retail = (string)$attributes['Retail'];

		if ( $wholesale == 'true' ) {
			$this->_pricetypes[$guid] = 'wholesale';
		} else if ( $retail == 'true' ) {
			$this->_pricetypes[$guid] = 'retail';
		} else {
			$this->_pricetypes[$guid] = '';
		}
		return true;
	}









	/* =========
     * Цены
     * ========= */
	// Открывающий тэг <Prices>
	protected function start_prices($reader)
	{
		// Проверяем, есть ли откуда брать id товаров
		if ( !array_key_exists('{{products}}', $this->_cashe) ) {
			$exists = $this->_db->createCommand()->select('id, guid')->from('{{products}}')->queryAll();
			$this->_cashe['{{products}}'] = CHtml::listData($exists, 'guid', 'id');
		}

		// Проверяем, есть ли откуда брать id характеристик
		if ( !array_key_exists('{{characteristics}}', $this->_cashe) ) {
			$exists = $this->_db->createCommand()->select('id, guid')->from('{{characteristics}}')->queryAll();
			$this->_cashe['{{characteristics}}'] = CHtml::listData($exists, 'guid', 'id');
		}

		// Проверяем, есть ли откуда брать id партнеров
		if ( !array_key_exists('{{partners}}', $this->_cashe) ) {
			$exists = $this->_db->createCommand()->select('id, guid')->from('{{partners}}')->queryAll();
			$this->_cashe['{{partners}}'] = CHtml::listData($exists, 'guid', 'id');
		}

		$exists = $this->_db->createCommand()->select('id, partner_id, product_id, characteristic_id, type')->from('{{prices}}')->queryAll();
		$this->_cashe['{{prices}}'] = array();
		foreach ( $exists as $price_row ) {
			$key = "{$price_row['product_id']}-{$price_row['characteristic_id']}-{$price_row['partner_id']}-{$price_row['type']}";
			$this->_cashe['{{prices}}'][$key] = $price_row['id'];
		}

		return true;
	}


	// Функция обработки цен
	protected function parse_price($reader)
	{
		$date = date('Y-m-d H:i:s');
		$xml = $reader->expandSimpleXml();
		$attributes = $xml->attributes();


		if ( $this->_mode === self::MODE_INSERT_OR_UPDATE ) {
			$columns = array(
				'partner_id' => $this->_cashe['{{partners}}'][(string)$attributes['PartnerGUID']],
				'characteristic_id' => $this->_cashe['{{characteristics}}'][(string)$attributes['CharacteristicGUID']],
				'product_id' => $this->_cashe['{{products}}'][(string)$attributes['ProductGUID']],
				'type' => $this->_pricetypes[(string)$attributes['PriceTypeGUID']],
				'type_guid' => (string)$attributes['PriceTypeGUID'],
				'value' => (string)$attributes['PriceValue']
			);

			$key = "{$columns['product_id']}-{$columns['characteristic_id']}-{$columns['partner_id']}-{$columns['type']}";
			if ( array_key_exists($key, $this->_cashe['{{prices}}']) ) {
				$id = $this->_cashe['{{prices}}'][$key];
				if ( $this->_db->createCommand()->update('{{prices}}', array(
					'value' => $columns['value'],
					'update_time' => $date,
				), 'id=:id', array(':id' => $id)) ) {
					$this->_updated++;
				}
			} else {
				$columns['update_time'] = $columns['create_time'] = $date;
				if ( $this->_db->createCommand()->insert('{{prices}}', $columns) ) {
					$id = $this->_db->getLastInsertId();
					$this->_cashe['{{prices}}'][$key] = $id;
					$this->_inserted++;
				}
			}
		} else if ( $this->_mode === self::MODE_REMOVE ) {
			$params = array(
				':partner_id' => $this->_cashe['{{partners}}'][(string)$attributes['PartnerGUID']],
				':characteristic_id' => $this->_cashe['{{characteristics}}'][(string)$attributes['CharacteristicGUID']],
				':product_id' => $this->_cashe['{{products}}'][(string)$attributes['ProductGUID']],
				':type_guid' => (string)$attributes['PriceTypeGUID'],
			);
			$this->_removed += $this->remove('{{prices}}', 'partner_id=:partner_id AND characteristic_id=:characteristic_id AND product_id=:product_id AND type_guid=:type_guid', $params);
		}


		return true;
	}


	// Закрывающий тэг </Prices>
	protected function finish_prices($reader)
	{
		unset($this->_cashe['{{products}}']);
		unset($this->_cashe['{{characteristics}}']);
		unset($this->_cashe['{{partners}}']);
		return true;
	}
}
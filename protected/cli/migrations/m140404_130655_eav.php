<?php
/**
 * Миграция m140404_130655_eav
 *
 * @property string $prefix
 */
 
class m140404_130655_eav extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'
	private $dropped = array('{{product_attributes}}', '{{product_attribute_values}}', '{{attribute_groups}}');
 
    public function safeUp()
    {
        $this->_checkTables();
 
        $this->createTable('{{product_attributes}}', array(
            'id' => 'pk', // auto increment
			'type_id' => 'integer NOT NULL COMMENT "Тип товара"',
			'sort' => 'integer NOT NULL',
			'alias' => 'string NOT NULL',
			'title' => 'string NOT NULL',
			'field_type' => 'varchar(20) NOT NULL',
			'variants' => 'text NOT NULL',
			'default' => 'string NOT NULL',
			'inshort' => 'smallint(1) NOT NULL',
        ),
        'ENGINE=MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci');
		$this->createIndex('type_id', '{{product_attributes}}', 'type_id');

		$this->createTable('{{product_attribute_values}}', array(
			'id' => 'pk', // auto increment
			'product_id' => 'integer NOT NULL',
			'attribute_id' => 'integer NOT NULL',
			'value' => 'string NOT NULL DEFAULT ""',
		),
		'ENGINE=MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci');

		$this->createTable('{{attribute_groups}}', array(
			'id' => 'pk', // auto increment
			'name' => 'integer NOT NULL',
			'alias' => 'integer NOT NULL',
		),
		'ENGINE=MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci');
    }
 
    public function safeDown()
    {
		$this->dropIndex('type_id', '{{product_attributes}}');
        $this->_checkTables();
    }
 
    /**
     * Удаляет таблицы, указанные в $this->dropped из базы.
     * Наименование таблиц могут сожержать двойные фигурные скобки для указания
     * необходимости добавления префикса, например, если указано имя {{table}}
     * в действительности будет удалена таблица 'prefix_table'.
     * Префикс таблиц задается в файле конфигурации (для консоли).
     */
    private function _checkTables ()
    {
        if (empty($this->dropped)) return;
 
        $table_names = $this->getDbConnection()->getSchema()->getTableNames();
        foreach ($this->dropped as $table) {
            if (in_array($this->tableName($table), $table_names)) {
                $this->dropTable($table);
            }
        }
    }
 
    /**
     * Добавляет префикс таблицы при необходимости
     * @param $name - имя таблицы, заключенное в скобки, например {{имя}}
     * @return string
     */
    protected function tableName($name)
    {
        if($this->getDbConnection()->tablePrefix!==null && strpos($name,'{{')!==false)
            $realName=preg_replace('/{{(.*?)}}/',$this->getDbConnection()->tablePrefix.'$1',$name);
        else
            $realName=$name;
        return $realName;
    }
 
    /**
     * Получение установленного префикса таблиц базы данных
     * @return mixed
     */
    protected function getPrefix(){
        return $this->getDbConnection()->tablePrefix;
    }
}
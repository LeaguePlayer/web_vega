<?php
/**
 * Миграция m140402_055535_prices
 *
 * @property string $prefix
 */
 
class m140402_055535_prices extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'
	private $dropped = array('{{prices}}');
 
    public function safeUp()
    {
        $this->_checkTables();
 
        $this->createTable('{{prices}}', array(
			'id' => 'pk',
			'product_id' => "varchar(40) NOT NULL",
			'characteristic_id' => "varchar(40) NOT NULL",
			'partner_id' => "varchar(40)",
			'type' => "VARCHAR(20) NOT NULL",
			'type_guid' => "VARCHAR(40) NOT NULL",
			'value' => "DECIMAL(10,2) NOT NULL",
            'create_time' => "datetime COMMENT 'Дата создания'",
            'update_time' => "datetime COMMENT 'Дата последнего редактирования'",
        ),
        'ENGINE=MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci');

		$this->createIndex('prices_unique_index', '{{prices}}', 'product_id, characteristic_id, partner_id, type', true);
		$this->createIndex('prices_unique_index2', '{{prices}}', 'product_id, characteristic_id, partner_id, type_guid', true);
    }
 
    public function safeDown()
    {
		$this->dropIndex('prices_unique_index', '{{prices}}');
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
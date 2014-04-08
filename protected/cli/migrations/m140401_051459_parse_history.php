<?php
/**
 * Миграция m140401_051459_parse_history
 *
 * @property string $prefix
 */
 
class m140401_051459_parse_history extends CDbMigration
{
    // таблицы к удалению, можно использовать '{{table}}'
	private $dropped = array('{{parse_history}}');
 
    public function safeUp()
    {
        $this->_checkTables();
 
        $this->createTable('{{parse_history}}', array(
            'id' => 'pk', // auto increment
			'file_name' => "string NOT NULL COMMENT 'Имя xml-файла'",
			'inserted_rows' => "integer NOT NULL COMMENT 'Загружено строк'",
			'updated_rows' => "integer NOT NULL COMMENT 'Обновлено строк'",
			'removed_rows' => "integer NOT NULL COMMENT 'Удалено строк'",
			'success' => "int(1) NOT NULL COMMENT 'Успешно считано?'",
			'description' => "text COMMENT 'Описание'",
        ),
        'ENGINE=MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci');
    }
 
    public function safeDown()
    {
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
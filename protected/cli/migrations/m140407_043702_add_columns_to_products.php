<?php
/**
 * Миграция m140407_043702_add_columns_to_products
 *
 * @property string $prefix
 */
 
class m140407_043702_add_columns_to_products extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('{{products}}', 'img_sample', 'string');
    }
 
    public function safeDown()
    {
		$this->dropColumn('{{products}}', 'img_sample');
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
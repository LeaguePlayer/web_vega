<?php

/**
* This is the model class for table "{{characteristics}}".
*
* The followings are the available columns in table '{{characteristics}}':
    * @property integer $id
    * @property string $guid
    * @property string $name
    * @property string $full_name
    * @property string $create_time
    * @property string $update_time
*/
class Characteristic extends CActiveRecord
{
    public function tableName()
    {
        return '{{characteristics}}';
    }


    public function rules()
    {
        return array(
            array('guid, name', 'length', 'max'=>40),
            array('full_name', 'length', 'max'=>255),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            array('id, guid, name, full_name, create_time, update_time', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
			'price'=>array(self::HAS_ONE, 'Price', 'characteristic_id')
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'guid' => 'GUID',
            'name' => 'Name',
            'full_name' => 'Full Name',
            'create_time' => 'Дата создания',
            'update_time' => 'Дата последнего редактирования',
        );
    }


    public function behaviors()
    {
        return CMap::mergeArray(parent::behaviors(), array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
			),
        ));
    }

    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('guid',$this->guid,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('full_name',$this->full_name,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


}

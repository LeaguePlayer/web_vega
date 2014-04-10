<?php

/**
* This is the model class for table "{{prices}}".
*
* The followings are the available columns in table '{{prices}}':
    * @property integer $id
    * @property string $product_id
    * @property string $characteristic_id
    * @property string $partner_id
    * @property string $type
    * @property string $type_guid
    * @property string $value
    * @property string $create_time
    * @property string $update_time
*/
class Price extends CActiveRecord
{
    public function tableName()
    {
        return '{{prices}}';
    }


    public function rules()
    {
        return array(
            array('product_id, characteristic_id, type, type_guid, value', 'required'),
            array('product_id, characteristic_id, partner_id, type_guid', 'length', 'max'=>40),
            array('type', 'length', 'max'=>20),
            array('value', 'length', 'max'=>10),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            array('id, product_id, characteristic_id, partner_id, type, type_guid, value, create_time, update_time', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
			'product'=>array(self::BELONGS_TO, 'Product', 'product_id'),
			'characteristic'=>array(self::BELONGS_TO, 'Characteristic', 'characteristic_id'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'product_id' => 'Product',
            'characteristic_id' => 'Characteristic',
            'partner_id' => 'Partner',
            'type' => 'Type',
            'type_guid' => 'Type Guid',
            'value' => 'Value',
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
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('characteristic_id',$this->characteristic_id,true);
		$criteria->compare('partner_id',$this->partner_id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('type_guid',$this->type_guid,true);
		$criteria->compare('value',$this->value,true);
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

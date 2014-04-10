<?php

/**
* This is the model class for table "{{product_attribute_values}}".
*
* The followings are the available columns in table '{{product_attribute_values}}':
    * @property integer $id
    * @property integer $product_id
    * @property integer $attribute_id
    * @property string $value
*/
class ProductAttributeValue extends CActiveRecord
{
    public function tableName()
    {
        return '{{product_attribute_values}}';
    }


    public function rules()
    {
        return array(
            array('product_id, attribute_id', 'required'),
            array('product_id, attribute_id', 'numerical', 'integerOnly'=>true),
            array('value', 'length', 'max'=>255),
            // The following rule is used by search().
            array('id, product_id, attribute_id, value', 'safe', 'on'=>'search'),
        );
    }


	public function relations()
	{
		return array(
			'product'=>array(self::BELONGS_TO, 'Product', 'product_id'),
			'attribute'=>array(self::BELONGS_TO, 'ProductAttribute', 'attribute_id'),
		);
	}


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'product_id' => 'Product',
            'attribute_id' => 'Attribute',
            'value' => 'Значение',
        );
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('attribute_id',$this->attribute_id);
		$criteria->compare('value',$this->value,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

	private $_value;
	public function getDisplayValue()
	{
		if ( $this->_value === null ) {
			if ( $this->attribute->field_type == ProductAttribute::FIELD_TYPE_STRING || $this->attribute->field_type == ProductAttribute::FIELD_TYPE_TEXT ) {
				$this->_value = $this->value ? $this->value : $this->attribute->default;
			} else {
				$variants = $this->attribute->decodeVariants();
				$this->_value = $variants[$this->value];
			}
		}
		return $this->_value;
	}
}

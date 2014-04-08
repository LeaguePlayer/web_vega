<?php

/**
* This is the model class for table "{{product_attributes}}".
*
* The followings are the available columns in table '{{product_attributes}}':
    * @property integer $id
    * @property integer $type_id
    * @property integer $sort
    * @property string $alias
    * @property string $title
    * @property string $variants
    * @property string $default
    * @property integer $inshort
*/
class ProductAttribute extends CActiveRecord
{
	const FIELD_TYPE_STRING = 'string';
	const FIELD_TYPE_TEXT = 'text';
	const FIELD_TYPE_CHECKBOX = 'checkbox';
	const FIELD_TYPE_RADIO = 'radio';
	const FIELD_TYPE_SELECT = 'select';


	public static function getFieldTypes()
	{
		return array(
			self::FIELD_TYPE_STRING => 'textfield',
			self::FIELD_TYPE_TEXT => 'textarea',
			self::FIELD_TYPE_CHECKBOX => 'checkbox',
			//self::FIELD_TYPE_RADIO => 'radio',
			self::FIELD_TYPE_SELECT => 'select',
		);
	}

	public function getFieldType()
	{
		$types = self::getFieldTypes();
		return $types[$this->fie];
	}

    public function tableName()
    {
        return '{{product_attributes}}';
    }


    public function rules()
    {
        return array(
			array('alias, title, field_type', 'required'),
			array('alias', 'unique'),
            array('type_id, sort, inshort', 'numerical', 'integerOnly'=>true),
			array('field_type', 'length', 'max'=>20),
            array('alias, title, default', 'length', 'max'=>255),
            array('variants', 'safe'),
            // The following rule is used by search().
            array('id, type_id, sort, alias, title, variants, default, inshort', 'safe', 'on'=>'search'),
        );
    }


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'type' => array(self::BELONGS_TO, 'ProductType', 'type_id'),
			'attribute_values' => array(self::HAS_MANY, 'ProductAttributeValue', 'attribute_id'),
		);
	}


	public function defaultScope()
	{
		return array(
			'order' => 'sort'
		);
	}


    public function attributeLabels()
    {
		return array(
			'id' => 'ID',
			'type_id' => 'Тип товара',
			'sort' => 'Позиция',
			'alias' => 'Идентификатор',
			'title' => 'Название атрибута',
			'field_type' => 'Тип атрибута',
			'variants' => 'Возможные варианты',
			'default' => 'Значение по-умолчанию',
			'inshort' => 'Показывать в общем списке',
			'defaultValue' => 'Значение по-умолчанию',
		);
    }



    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('variants',$this->variants,true);
		$criteria->compare('default',$this->default,true);
		$criteria->compare('inshort',$this->inshort);
        $criteria->order = 'sort';
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

	/** scope
	 * @param $type_id
	 * @return ShopAttribute
	 */
	public function type($type_id)
	{
		$this->getDbCriteria()->mergeWith(array(
			'condition' => 't.type_id=:type',
			'params'=>array(':type'=>$type_id),
		));
		return $this;
	}

	public function beforeDelete()
	{
		if (parent::beforeDelete())
		{
			foreach ($this->attribute_values as $value)
				$value->delete();

			return true;
		}

		return false;
	}

	private $_variants;
	public function decodeVariants()
	{
		if ( $this->_variants === null ) {
			$parts = explode('|', $this->variants);
			$out = array();
			foreach ( $parts as $part ) {
				$pair = explode('=', $part);
				if ( !empty($pair) ) {
					$key = trim($pair[0]);
					$out[$key] = trim($pair[1]);
				}
			}
			$this->_variants = $out;
		}
		return $this->_variants;
	}


	public function getDefaultValue()
	{
		if ( $this->field_type == 'textfield' || $this->field_type == 'textarea' ) {
			return $this->default;

		}
		$variants = $this->decodeVariants();
		return isset($variants[$this->default]) ? $variants[$this->default] : $this->default;
	}
}

<?php

/**
* This is the model class for table "{{products}}".
*
* The followings are the available columns in table '{{products}}':
    * @property integer $id
    * @property string $guid
    * @property string $article
    * @property string $name
    * @property string $translit_name
    * @property string $full_name
    * @property string $description
    * @property string $category_id
    * @property integer $sort
    * @property string $create_time
    * @property string $update_time
*/
class Product extends EActiveRecord
{
    public function tableName()
    {
        return '{{products}}';
    }


    public function rules()
    {
        return array(
            array('sort', 'numerical', 'integerOnly'=>true),
            array('guid, article, category_id', 'length', 'max'=>40),
            array('name, translit_name, full_name', 'length', 'max'=>255),
            array('description, create_time, update_time', 'safe'),
            // The following rule is used by search().
            array('id, guid, article, name, translit_name, category_id, sort, create_time, update_time', 'safe', 'on'=>'search'),
        	array('url', 'unsafe', 'on' => 'search')
		);
    }


    public function relations()
    {
        return array(
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'inshort_attribute_values' => array(self::HAS_MANY, 'ProductAttributeValue', 'product_id',
				'condition'=>'attribute.inshort = 1',
				'order'=>'attribute.sort ASC',
				'with'=>'attribute',
			),
			'all_attribute_values' => array(self::HAS_MANY, 'ProductAttributeValue', 'product_id',
				'order'=>'attribute.sort ASC',
				'with'=>'attribute',
			),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'guid' => 'GUID товара',
            'article' => 'Артикул товара',
            'name' => 'Наименование товара',
            'translit_name' => 'Translit Name',
            'full_name' => 'Полное наименование товара',
            'description' => 'Описание товара',
            'category_id' => 'Ссылка на категорию',
            'sort' => 'Вес для сортировки',
            'create_time' => 'Дата создания',
            'update_time' => 'Дата последнего редактирования',
			'img_sample' => 'Образец'
        );
    }


    public function behaviors()
    {
        return CMap::mergeArray(parent::behaviors(), array(
			'photo' => array(
				'class' => 'application.behaviors.UploadableImageBehavior',
				'attributeName' => 'img_sample',
				'versions' => array(
					'icon' => array(
						'centeredpreview' => array(90, 90),
					),
					'small' => array(
						'resize' => array(200, 180),
					)
				),
			),
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
		$criteria->compare('article',$this->article,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('translit_name',$this->translit_name,true);

		if ( is_numeric($this->category_id) )
			$criteria->compare('category_id',$this->category_id);
		else if ( is_array($this->category_id) )
			$criteria->addInCondition('category_id',$this->category_id);

		$criteria->compare('sort',$this->sort);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
        $criteria->order = 'name';
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }


    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


	public function beforeDelete()
	{
		if (parent::beforeDelete())
		{
			foreach ($this->all_attribute_values as $val)
				$val->delete();

			return true;
		}

		return false;
	}

	private $_url;
	public function getUrl()
	{
		if ( !$this->category )
			return '';

		if ($this->_url === null)
			$this->_url = Yii::app()->request->baseUrl . '/catalog/' . $this->category->getPath() . '/' . $this->id;

		return $this->_url;
	}
}

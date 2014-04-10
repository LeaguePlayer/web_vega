<?php

/**
* This is the model class for table "{{categories}}".
*
* The followings are the available columns in table '{{categories}}':
    * @property integer $id
    * @property string $name
    * @property string $translit_name
    * @property integer $is_brand
    * @property integer $level
    * @property string $parent_id
    * @property string $img_preview
    * @property integer $status
    * @property integer $sort
    * @property string $create_time
    * @property string $update_time
*/
class Category extends EActiveRecord
{
    public function tableName()
    {
        return '{{categories}}';
    }


    public function rules()
    {
        return array(
            array('id, name', 'required'),
            array('is_brand, level, status, sort', 'numerical', 'integerOnly'=>true),
            array('id, parent_id', 'length', 'max'=>40),
            array('name, translit_name, img_preview', 'length', 'max'=>255),
            array('create_time, update_time', 'safe'),
            // The following rule is used by search().
            array('id, name, translit_name, is_brand, level, parent_id, img_preview, status, sort, create_time, update_time', 'safe', 'on'=>'search'),
        );
    }


    public function relations()
    {
        return array(
            'children' => array(self::HAS_MANY, 'Category', 'parent_id'),
            'parent' => array(self::BELONGS_TO, 'Category', 'parent_id'),
			'products' => array(self::HAS_MANY, 'Product', 'category_id'),
			'attrs_assoc' => array(self::HAS_MANY, 'CategoryAttribute', 'category_id'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'guid' => 'GUID категории',
            'name' => 'Наименование категории',
            'translit_name' => 'Идентификационное имя',
            'is_brand' => 'Бренд?',
            'level' => 'Вложенность',
            'img_preview' => 'Изображение',
			'status' => 'Статус',
			'sort' => 'Вес для сортировки',
			'create_time' => 'Дата создания',
			'update_time' => 'Дата последнего редактирования',
			'parent.name' => 'Родительская категория',
		);
    }


    public function behaviors()
    {
        return CMap::mergeArray(parent::behaviors(), array(
			'imgBehaviorPreview' => array(
				'class' => 'application.behaviors.UploadableImageBehavior',
				'attributeName' => 'img_preview',
				'versions' => array(
					'icon' => array(
						'resize' => array(0, 50),
					),
					'small' => array(
						'centeredpreview' => array(224, 224),
					)
				),
			),
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
			),
            'categoryBehavior' => array(
                'class' => 'DCategoryTreeBehavior',
                'titleAttribute' => 'name',
                'aliasAttribute' => 'translit_name',
                'iconAttribute' => 'preview',
				'requestPathAttribute' => 'url'
            )
        ));
    }

    public function getPreview()
    {
        return $this->imgBehaviorPreview->getImageUrl('small');
    }

    public function getUrl()
    {
        return Yii::app()->createUrl('category/view', array('url' => $this->translit_name));
    }

//    public function getLinkActive()
//    {
//        return $this->translit_name == $_GET['url'];
//    }

    public function search()
    {
        $criteria=new CDbCriteria;
		$criteria->compare('name',$this->name,true);
        return new DTreeActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getIndent()
    {
        return $this->level;
    }

    public function setIndent($value)
    {
        $this->level = $value;
    }

    public function beforeSave()
    {
    	if (parent::beforeSave()) {
    		$this->translit_name = SiteHelper::translit($this->name);
    		return true;
    	}
    	return false;
    }

    public function findByUrl($url)
    {
        return $this->findByAttributes(array(
            'translit_name' => $url
        ));
    }


	private $_attrs_assoc;
	public function getLinkedAttrs()
	{
		if ( $this->_attrs_assoc === null ) {
			$this->_attrs_assoc = array();
			// Полный путь от текущей категории до родительской
			$pathBranch = $this->getFullBranch();
			// Ищем среди этой и родительских категориях те, к которым уже привязаны атрибуты
			$criteria = new CDbCriteria();
			$criteria->addInCondition('category_id', $pathBranch);
			$attr_types = Yii::app()->db->createCommand()
				->select('category_id, attribute_id')
				->from('{{category_attributes}}')
				->where($criteria->condition, $criteria->params)
				->order('category_id')
				->queryAll();

			$attr_ids = array();
			$find_category = false;
			foreach ( $pathBranch as $category_id ) {
				foreach ( $attr_types as $type ) {
					if ( $category_id == $type['category_id'] ) {
						$attr_ids[] = $type['attribute_id'];
						$find_category = true;
					}
				}
				if ( $find_category ) break;
			}

			$criteria = new CDbCriteria();
			$criteria->addInCondition('id', $attr_ids);
			$this->_attrs_assoc = ProductAttribute::model()->findAll($criteria);


			$this->_attrs_assoc = $attr_ids;
		}
		return $this->_attrs_assoc;
	}

	private $_attrs;
	public function getAttrs()
	{
		if ( $this->_attrs === null ) {
			$this->_attrs = array();
			$attr_ids = $this->getLinkedAttrs();
			$criteria = new CDbCriteria();
			$criteria->addInCondition('id', $attr_ids);
			$this->_attrs = ProductAttribute::model()->findAll($criteria);
		}
		return $this->_attrs;
	}
}

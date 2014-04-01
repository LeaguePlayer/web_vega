<?php

/**
* This is the model class for table "{{categories}}".
*
* The followings are the available columns in table '{{categories}}':
    * @property string $id
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
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'GUID категории',
            'name' => 'Наименование категории',
            'translit_name' => 'Translit Name',
            'is_brand' => 'Бренд?',
            'level' => 'Level',
            'parent_id' => 'Parent',
            'img_preview' => 'Изображение',
            'status' => 'Статус',
            'sort' => 'Вес для сортировки',
            'create_time' => 'Дата создания',
            'update_time' => 'Дата последнего редактирования',
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

    public function getLinkActive()
    {
        return $this->translit_name == $_GET['url'];
    }

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
}

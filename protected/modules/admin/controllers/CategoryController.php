<?php

class CategoryController extends AdminController
{
	public function actionUpdate($id)
	{
		$model = $this->loadModel('Category', $id);

		$allAttrs = ProductAttribute::model()->findAll();

		if (isset($_POST['Category']['deletePhoto'])) {
			$model->deletePhoto();
			if ( Yii::app()->request->isAjaxRequest ) {
				Yii::app()->end();
			}
		}

		if(isset($_POST['Category']))
		{
			$model->attributes = $_POST['Category'];

			$success = $model->save();
			if( $success ) {
				$check_attributes = $_POST['Category']['check_attributes'] ? $_POST['Category']['check_attributes'] : array();
				$this->refreshCategoryAttrs($model, $check_attributes);
				$this->redirect(array('/admin/catalog/index', 'open_category' => $model->id));
			}
		}


		$this->render('update', array(
			'model' => $model,
			'all_attrs' => $allAttrs,
		));
	}


	protected function refreshCategoryAttrs(Category $model, $check_attributes)
	{
		$specified_attrs = CHtml::listData($model->attrs_assoc, 'id', 'attribute_id');
		$linked_attrs = $model->getLinkedAttrs();

		if ( $this->array_equal($specified_attrs, $linked_attrs) ) {
			$attrs_for_add = array_diff($check_attributes, $linked_attrs);
		} else {
			$attrs_for_add = $check_attributes;
		}
		$attrs_for_add += array(0); // Нулевой атрибут
		$attrs_for_del = array_diff($linked_attrs, $check_attributes);

		if ( empty($attrs_for_del) && empty($attrs_for_add) ) {
			return;
		}

		$categories = $model->getChildsArray();
		array_unshift($categories, $model->id);

		if ( !empty($attrs_for_del) ) {
			$delCriteria = new CDbCriteria();
			$delCriteria->addInCondition('attribute_id', $attrs_for_del);
			foreach ( $categories as $category_id ) {
				$criteria = clone $delCriteria;
				$criteria->compare('category_id', $category_id);
				Yii::app()->db->createCommand()->delete('{{category_attributes}}', $criteria->condition, $criteria->params);
			}
		}

		if ( !empty($attrs_for_add) ) {
			$criteria = new CDbCriteria();
			$criteria->addInCondition('category_id', $categories);
			$specified_categories = CHtml::listData( Yii::app()->db->createCommand()
				->selectDistinct('category_id')
				->from('{{category_attributes}}')
				->where($criteria->condition, $criteria->params)
				->queryAll(), 'category_id', 'category_id' );

			$specified_categories += array($model->id => $model->id);

			foreach ( $specified_categories as $category_id ) {
				foreach ( $attrs_for_add as $attr_id ) {
					$count = Yii::app()->db->createCommand()
						->select('count(id)')
						->from('{{category_attributes}}')
						->where('category_id=:cat AND attribute_id=:attr', array(':cat'=>$category_id, ':attr'=>$attr_id))
						->queryScalar();

					if ( $count > 0 )
						continue;

					Yii::app()->db->createCommand()->insert('{{category_attributes}}', array(
						'category_id' => $category_id,
						'attribute_id' => $attr_id,
					));
				}
			}
		}
	}


	function array_equal($a, $b)
	{
		return (is_array($a) && is_array($b) && array_diff($a, $b) === array_diff($b, $a));
	}
}

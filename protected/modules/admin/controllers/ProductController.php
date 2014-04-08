<?php
/**
 * User: megakuzmitch
 * Date: 04.04.14
 * Time: 18:06
 */

class ProductController extends AdminController
{
	public function actionUpdate($id)
	{
		$model = $this->loadModel('Product', $id);

		$attrs = ProductAttribute::model()->findAll();
		// Строим массив полей
		$items = array();
		foreach ($attrs as $attr)
		{
			$items[$attr->alias] = array(
				'model'=>$attr,
				'value'=>$attr->default,
				'disabled'=>true // По-умолчанию се атрибуты отключены
			);
		}

		// Заполняю текущими значениями
		$attrValues = $model->all_attribute_values;
		foreach ($attrValues as $attrValue) {
			$items[$attrValue->attribute->alias]['value'] = $attrValue->value;
			$items[$attrValue->attribute->alias]['disabled'] = false; // Включаю найденные у товары атрибуты в форме
		}


		if ( isset($_POST['Product']) ) {
			$model->attributes = $_POST['Product'];
			if ( $model->save() ) {
				$newIds = array();
				if ( isset($_POST['ProductAttributeValue']) ) {
					foreach ($_POST['ProductAttributeValue'] as $attr_id => $value) {
						$attrValue = ProductAttributeValue::model()->findByAttributes(array(
							'attribute_id' => $attr_id,
							'product_id' => $model->id
						));
						if ( $attrValue === null ) {
							$attrValue = new ProductAttributeValue();
							$attrValue->attribute_id = $attr_id;
							$attrValue->product_id = $model->id;
						}
						$attrValue->value = $value;
						if ( $attrValue->save() ) {
							$newIds[] = $attrValue->id;
						}
					}
				}
				$criteria = new CDbCriteria();
				$criteria->compare('product_id', $model->id);
				$criteria->addNotInCondition('id', $newIds);
				ProductAttributeValue::model()->deleteAll($criteria);
				$this->redirect(array('/admin/catalog/index', 'open_category'=>$model->category->id) + $this->grabGridSearchParams());
			}
		}

		$this->render('update', array(
			'model' => $model,
			'items' => $items
		));
	}
}
<?php
/**
 * User: megakuzmitch
 * Date: 02.04.14
 * Time: 15:48
 */

class CatalogController extends AdminController
{
	public $defaultAction = 'index';

	public function actions()
	{
		return array();
	}


	public function actionIndex($open_category = false)
	{
		$productFinder = new Product('search');
		$productFinder->unsetAttributes();
		$productFinder->category_id = $open_category;
		if ( isset($_GET['Product']) ) {
			$productFinder->attributes = $_GET['Product'];
		}

		$categories = Category::model()->getMenuList(2);

		$this->layout = 'simple';
		$this->render('index', array(
			'categories' => $categories,
			'productFinder' => $productFinder,
			'open_category' => $open_category
		));
	}
}
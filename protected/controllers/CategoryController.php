<?php

class CategoryController extends FrontController
{
	public $layout='//layouts/main';

	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	
	public function actionView($url)
	{
        $model = Category::model()->findByUrl($url);
        if ( !$model ) {
            throw new CHttpException(404, 'Категория не найдена');
        }

		$this->render('view',array(
			'model'=>$model,
		));
	}

	
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Category');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
}

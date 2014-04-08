<?php

class CategoryController extends AdminController
{
	public function actionUpdate($id)
	{
		$model = $this->loadModel('Category', $id);

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
				$this->redirect(array('/admin/catalog/index', 'open_category' => $model->id));
			}
		}
		$this->render('update', array('model' => $model));
	}
}

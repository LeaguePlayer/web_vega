<?php

class SiteController extends FrontController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
        $criteria = new CDbCriteria();
        $criteria->order = 'sort';
        $criteria->addCondition('t.parent_id IS NULL OR t.parent_id=""');
        $categories = Category::model()->findAll($criteria);

        $this->buildCategories();
//        $this->buildMenu();

        $this->title = Yii::app()->config->get('app.name');
		$this->render('index', array(
            'categories' => $categories
        ));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

    public function actionParse()
    {
        $start_time = SiteHelper::getmicrotime();

        $path = Yii::getPathOfAlias('webroot.ExchangeVega').DIRECTORY_SEPARATOR;
        $files = glob($path.'*.xml');
        if ( !count($files) ) {
            echo "xml-file not found in /ExchangeVega folder";
            Yii::app()->end();
        }

        rsort($files);
        $parser = new VegaXMLParser();
        echo "Последняя выгрузка - {$files[0]}</br>";
        $parser->open($files[0]);
        $parser->parse();

        echo "Время обработки: ".(SiteHelper::getmicrotime() - $start_time).' сек';
    }
}
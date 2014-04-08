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

    public function actionParse($filename = '')
    {
        $start_time = SiteHelper::getmicrotime();
        $parser = new VegaXMLParser();

        // читаем список файлов
        $path = Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR;

		// если файл задан явно, парсим только его
		if ( $filename ) {
			$parser->open($path.$filename.'.xml');
			$parse_info = array();
			$parser->parse($parse_info);
			echo "Время обработки: ".(SiteHelper::getmicrotime() - $start_time).' сек';
			var_dump($path.$filename.'.xml', $parse_info);
			exit;
		}



        $files = glob($path.'*.xml');
        if ( !count($files) ) {
            echo "xml-files not found in /ExchangeVega folder";
            Yii::app()->end();
        }

        $start_file = '';
        // поиск информации о последней успешной выгрузке
        $last_parse_info = Yii::app()->db->createCommand()
            ->select('*')->from('{{parse_history}}')
            ->where('success=1')
            ->order('file_name DESC')
            ->queryRow();


        if ( $last_parse_info ) {
            $start_file = $last_parse_info['file_name'];
        }

        // Поиск и парсинг новых файлов
        // Если при чтении какого-либо из файлов произойдет сбой,
        // то работа скрипта будет продолжена, но все операции начиная с этой будут помечены, как провальные
        // При следующем запуске скрипта парсер начнет обрабатывать все файлы, начиная с этого.
        sort($files);
        foreach ( $files as $file ) {
            $fileinfo = pathinfo($file);
            $filename = $fileinfo['filename'];
            // проверка имени файла на заданный шаблон
            if ( !preg_match('/^\d\d\d\d_\d\d_\d\d_\[\d\d_\d\d_\d\d\]$/', $filename) )
                continue;

            // обрабатывать ли файл
            if ( $filename <= $start_file )
                continue;

            try {
                $parser->open($file);
				$parse_info = array();
                $parser->parse($parse_info);
                Yii::app()->db->createCommand()->insert('{{parse_history}}', array(
                    'file_name' => $filename,
                    'success' => true,
					'inserted_rows' => $parse_info['inserted'],
					'updated_rows' => $parse_info['updated'],
					'removed_rows' => $parse_info['removed']
                ));
                echo $file.'<br>';
				var_dump($parse_info);
            } catch ( Exception $e ) {
                Yii::app()->db->createCommand()->insert('{{parse_history}}', array(
                    'file_name' => $filename,
                    'description' => $e->getMessage()
                ));
                continue;
            }
        }

        echo "Время обработки: ".(SiteHelper::getmicrotime() - $start_time).' сек';
    }
}
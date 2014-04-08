<?php

Yii::import('admin.actions.*');

class AdminController extends Controller
{
    public $layout = '/layouts/admin_columns';
	public $defaultAction = 'list';

    public function filters()
    {
        return array(
            array('auth.filters.AuthFilter'),
        );
    }
	
	public function actions()
    {
        return array(
            'list' => 'ListAction',
			'create' => 'CreateAction',
            'update' => 'UpdateAction',
            'delete' => 'DeleteAction',
            'restore' => 'RestoreAction',
            'view' => 'ViewAction',
            'sort' => 'SortAction',
        );
    }

    /**
     * Render SEO form for @param $model
     * @return mixed|string
     */
    public function getSeoForm($model) {
        $out = '';
        if($model->metaData->hasRelation('seo')) {
            if(isset($model->seo_id)){
                $seo = Seo::model()->findByPk($model->seo_id);
            }
            if ( $seo === null ) {
                $seo = new Seo;
            }

            $out = $this->renderPartial('/seo/_form', array(
                'model' => $seo,
                'title' => $model->getAttributeLabel('seo_id'
            )), true);
        }
        return $out;
    }


	private $_grid_params;
	/**
	 * Возвращает массив $_GET-парамаметров для виджета CGridView
	 * Предполагается, что параметр виджета $pageVar = 'page', $sortVar = 'sort'
	 * @param mixed $model
	 * @return array|mixed
	 */
	public function grabGridSearchParams($model = null, $pageVar = 'page', $sortVar = 'sort')
	{
		if ( $this->_grid_params !== null )
			return $this->_grid_params;

		if ( $model instanceof CModel ) {
			$model_class = get_class($model);
		} else if ( is_string($model) ) {
			$model_class = $model;
		} else {
			$model_class = ucfirst($this->id);
		}
		$result = array();
		if ( isset($_GET[$model_class]) )
			$result = array($model_class => $_GET[$model_class]);
		$pageParam = "{$model_class}_{$pageVar}";
		if ( isset($_GET[$pageParam]) )
			$result += array($pageParam => $_GET[$pageParam]);

		$sortParam = "{$model_class}_{$sortVar}";
		if ( isset($_GET[$sortParam]) )
			$result += array($sortParam => $_GET[$sortParam]);
		return $this->_grid_params = $result;
	}
}
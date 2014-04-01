<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class FrontController extends Controller
{
    public $layout='//layouts/main';
    public $menu=array();
    public $breadcrumbs=array();

    // Массив категорий на текущей странице, включая вложенные
    public $categories=array();

    public function init() {
        parent::init();
        $this->title = Yii::app()->name;
    }

    //Check home page
    public function is_home(){
        return $this->route == 'site/index';
    }

    public function buildMenu($parent = null)
    {
        if ( !$parent )
            $parent = Menu::model()->roots()->find();
        $this->menu = Menu::model()->getMenuList(1, $parent);
    }

    public function buildCategories($current_category = null)
    {
        $this->categories = Category::model()->getMenuList(2, $current_category);
    }

    protected function beforeAction($action)
    {
        if ( parent::beforeAction($action) ) {
            $this->buildCategories();
            return true;
        }
        return false;
    }
}
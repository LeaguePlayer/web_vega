<?php
$this->breadcrumbs=array(
	"Список категорий"=>array('/admin/catalog/index', 'open_category' => $model->id),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('/admin/catalog/index', 'open_category' => $model->id)),
);
?>

<h1><?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
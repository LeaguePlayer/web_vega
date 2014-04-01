<?php
$this->breadcrumbs=array(
	"Список категорий"=>array('list'),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('list')),
);
?>

<h1><?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
<?php
$this->breadcrumbs=array(
	"Список атрибутов"=>array('list'),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('list')),
	array('label'=>'Добавить','url'=>array('create')),
);
?>

<h3>Редактирование атрибута <?= $model->title ?></h3>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
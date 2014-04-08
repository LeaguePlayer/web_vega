<?php
$this->breadcrumbs=array(
	"Список атрибутов"=>array('list'),
	'Создание',
);

$this->menu=array(
	array('label'=>'Список атрибутов','url'=>array('list')),
);
?>

<h1>Создание атрибута</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
$backUrl = array('/admin/catalog/index', 'open_category' => $model->category->id) + $this->grabGridSearchParams();

$this->breadcrumbs=array(
	"Каталог" => $backUrl,
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Каталог', 'url'=>$backUrl),
);
?>

<h1><?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model, 'backUrl'=>$backUrl, 'items'=>$items
)); ?>
<?php
$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
);
?>

<h3>Атрибуты товаров</h3>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'productAttribute-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('productattribute')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
    )',
	'columns'=>array(
		array(
			'class' => 'SortHandlerColumn'
		),
		array(
			'name' => 'title',
			'type' => 'raw',
			'value' => 'CHtml::link($data->title, array("update", "id" => $data->id))'
		),
		'field_type',
		'defaultValue',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}'
		),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("productAttribute");', CClientScript::POS_END) ;?>
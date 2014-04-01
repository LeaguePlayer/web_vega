

<h3>Управление категориями</h3>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'category-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
	'columns'=>array(
		array(
            'class'=>'EIndentColumn',
            'name'=>'name',
        ),
        array(
            'name'=>'img_preview',
            'type'=>'raw',
            'value'=>'$data->img_preview ? TbHtml::image($data->imgBehaviorPreview->getImageUrl("icon")) : ""',
            'filter'=>false,
        ),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}',
		),
	),
)); ?>
<div class='control-group'>
	<?php echo CHtml::activeLabelEx($model, 'img_preview'); ?>
	<?php echo $form->fileField($model,'img_preview', array('class'=>'span3')); ?>
	<div class='img_preview'>
		<?php if ( !empty($model->img_preview) ) echo TbHtml::imageRounded( $model->imgBehaviorPreview->getImageUrl('small') ) ; ?>
		<span class='deletePhoto btn btn-danger btn-mini' data-modelname='Category' data-attributename='Preview' <?php if(empty($model->img_preview)) echo "style='display:none;'"; ?>><i class='icon-remove icon-white'></i></span>
	</div>
	<?php echo $form->error($model, 'img_preview'); ?>
</div>


<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data' => $model,
	'attributes' => array(
		'guid',
		'name',
		'translit_name',
		array(
			'name'=>'parent.name',
			'type'=>'raw',
			'value'=>CHtml::link($model->parent->name, array('update', 'id' => $model->parent->id), array(
				'target' => '_blank',
			)),
		),
	)
)); ?>
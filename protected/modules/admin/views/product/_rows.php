<div class='control-group'>
	<?php echo CHtml::activeLabelEx($model, 'img_sample'); ?>
	<?php echo $form->fileField($model,'img_sample', array('class'=>'span3')); ?>
	<div class='img_preview'>
		<?php if ( !empty($model->img_sample) ) echo TbHtml::imageRounded( $model->photo->getImageUrl('normal') ) ; ?>
		<span class='deletePhoto btn btn-danger btn-mini' data-modelname='Product' data-attributename='Sample' <?php if(empty($model->img_sample)) echo "style='display:none;'"; ?>><i class='icon-remove icon-white'></i></span>
	</div>
	<?php echo $form->error($model, 'img_sample'); ?>
</div>


<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data' => $model,
	'attributes' => array_merge(array(
		'guid',
		'name',
		'full_name',
		'translit_name',
		'description',
		'characteristicsString'
	), $model->getAttrsDetailViewList()),
)); ?>
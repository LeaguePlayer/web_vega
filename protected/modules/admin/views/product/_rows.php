<div class='control-group'>
	<?php echo CHtml::activeLabelEx($model, 'img_sample'); ?>
	<?php echo $form->fileField($model,'img_sample', array('class'=>'span3')); ?>
	<div class='img_preview'>
		<?php if ( !empty($model->img_sample) ) echo TbHtml::imageRounded( $model->photo->getImageUrl('small') ) ; ?>
		<span class='deletePhoto btn btn-danger btn-mini' data-modelname='Product' data-attributename='Sample' <?php if(empty($model->img_sample)) echo "style='display:none;'"; ?>><i class='icon-remove icon-white'></i></span>
	</div>
	<?php echo $form->error($model, 'img_sample'); ?>
</div>

<?php echo $form->textFieldControlGroup($model,'article',array('class'=>'span8','maxlength'=>40)); ?>

<?php echo $form->textFieldControlGroup($model,'name',array('class'=>'span8','maxlength'=>255)); ?>

<?php echo $form->textFieldControlGroup($model,'translit_name',array('class'=>'span8','maxlength'=>255)); ?>

<?php echo $form->textFieldControlGroup($model,'full_name',array('class'=>'span8','maxlength'=>255)); ?>

<?php echo $form->textAreaControlGroup($model,'description',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
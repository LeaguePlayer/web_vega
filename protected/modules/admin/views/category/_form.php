<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'category-form',
	'enableAjaxValidation'=>false,
		'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>




<?php echo $form->errorSummary($model); ?>



<div class='control-group'>
    <?php echo CHtml::activeLabelEx($model, 'img_preview'); ?>
    <?php echo $form->fileField($model,'img_preview', array('class'=>'span3')); ?>
    <div class='img_preview'>
        <?php if ( !empty($model->img_preview) ) echo TbHtml::imageRounded( $model->imgBehaviorPreview->getImageUrl('small') ) ; ?>
        <span class='deletePhoto btn btn-danger btn-mini' data-modelname='Category' data-attributename='Preview' <?php if(empty($model->img_preview)) echo "style='display:none;'"; ?>><i class='icon-remove icon-white'></i></span>
    </div>
    <?php echo $form->error($model, 'img_preview'); ?>
</div>
<?php echo $form->textFieldControlGroup($model,'name',array('class'=>'span8','maxlength'=>255)); ?>



<div class="form-actions">
    <?php echo TbHtml::submitButton('Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
    <?php echo TbHtml::linkButton('Отмена', array('url'=>'/admin/category/list')); ?>
</div>




<?php $this->endWidget(); ?>

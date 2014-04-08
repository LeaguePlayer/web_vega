<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'product-attribute-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldControlGroup($model,'title',array('class'=>'span8','maxlength'=>255)); ?>

	<?php echo $form->textFieldControlGroup($model,'alias',array('class'=>'span8','maxlength'=>255)); ?>

	<?php echo $form->dropDownListControlGroup($model,'field_type',ProductAttribute::getFieldTypes(),array('class'=>'span8','maxlength'=>20)); ?>

	<?php echo $form->textAreaControlGroup($model,'variants',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldControlGroup($model,'default',array('class'=>'span8','maxlength'=>255)); ?>

	<div class="form-actions">
		<?php echo TbHtml::submitButton('Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
        <?php echo TbHtml::linkButton('Отмена', array('url'=>'/admin/productAttribute/list')); ?>
	</div>

<?php $this->endWidget(); ?>



<?php

	Yii::app()->clientScript->registerScript('translitInputs', "jQuery('input#ProductAttribute_title').writetranslit('input#ProductAttribute_alias')");

?>
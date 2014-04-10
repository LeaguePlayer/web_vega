<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'category-form',
	'enableAjaxValidation'=>false,
		'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>




<?php echo $form->errorSummary($model); ?>

<?php
	//$warningText = "<b>Внимание!</b> У тех товаров данной категории, у которых какой-либо из атрибутов уже задан, этот атрибут перезаписан не будет";
?>

<?php $this->widget('bootstrap.widgets.TbTabs', array(
	'tabs' => array(
		array(
			'label' => 'Основные параметры',
			'content' => $this->renderPartial('_rows', array(
				'form'=>$form,
				'model'=>$model,
			), true),
			'active' => true
		),
		array(
			'label' => 'Атрибуты',
			'content' => $this->renderPartial('_attributes', array(
				'form'=>$form,
				'model'=>$model,
				'all_attrs'=>$all_attrs,
			), true),
		),
	),
)); ?>



<div class="form-actions">
    <?php echo TbHtml::submitButton('Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
    <?php echo TbHtml::linkButton('Отмена', array('url'=>array('/admin/catalog/index', 'open_category' => $model->id))); ?>
</div>




<?php $this->endWidget(); ?>

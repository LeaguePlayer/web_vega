<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'product-form',
	'enableAjaxValidation'=>false,
		'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<?php echo $form->errorSummary($model); ?>

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
					'items'=>$items
				), true),
			),
		),
	)); ?>

	<div class="form-actions">
		<?php echo TbHtml::submitButton('Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
        <?php echo TbHtml::linkButton('Отмена', array('url' => $backUrl)); ?>
	</div>

<?php $this->endWidget(); ?>

<?php
Yii::app()->clientScript->registerScriptFile( $this->getAssetsUrl().'/js/productAttribute.form.js' );
Yii::app()->clientScript->registerCssFile( $this->getAssetsUrl().'/css/productAttribute.form.css' );
?>


<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'product-attribute-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldControlGroup($model,'title',array('class'=>'span8','maxlength'=>255)); ?>

	<?php echo $form->textFieldControlGroup($model,'alias',array('class'=>'span8','maxlength'=>255)); ?>

	<?php echo $form->dropDownListControlGroup($model,'field_type',ProductAttribute::getFieldTypes(),array('class'=>'span8','maxlength'=>20)); ?>

	<?php $variants = $model->decodeVariants() ?>

	<fieldset id="variants" class="variants">
		<legend>
			<?= $model->getAttributeLabel('variants') ?>
			<?= TbHtml::linkButton('Добавить', array(
				'icon' => TbHtml::ICON_PLUS,
				'size' => TbHtml::BUTTON_SIZE_MINI,
				'class' => 'add_variant'
			)) ?>
		</legend>
		<div class="control-group">
			<div class="controls">
				<?= $form->textArea($model, 'variants', array('class' => 'hide')) ?>
				<div class="variant_rows">
					<?php if ( !($model->field_type == ProductAttribute::FIELD_TYPE_CHECKBOX) ): ?>
						<?php foreach ( $variants as $key => $value ): ?>
							<div class="control-row">
								<input type="text" value="<?= $value ?>" />
							</div>
						<?php endforeach ?>
					<?php endif ?>
				</div>
				<div class="checkbox_variants">
					<div class="control-row">
						<div class="control-row">
							<input type="text" value="<?= $model->field_type == ProductAttribute::FIELD_TYPE_CHECKBOX ? $variants[0] : 'Нет' ?>" />
						</div>
						<div class="control-row">
							<input type="text" value="<?= $model->field_type == ProductAttribute::FIELD_TYPE_CHECKBOX ? $variants[1] : 'Да' ?>" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</fieldset>

	<?php
		if ( $model->field_type == ProductAttribute::FIELD_TYPE_STRING || $model->field_type == ProductAttribute::FIELD_TYPE_TEXT )
			echo $form->textFieldControlGroup($model,'default',array('class'=>'span8','maxlength'=>255));
		else
			echo $form->dropDownListControlGroup($model,'default', $variants, array('class'=>'span8','maxlength'=>255));
	?>


	<div class="form-actions">
		<?php echo TbHtml::submitButton('Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
        <?php echo TbHtml::linkButton('Отмена', array('url'=>'/admin/productAttribute/list')); ?>
	</div>

<?php $this->endWidget(); ?>



<?php

	Yii::app()->clientScript->registerScript('translitInputs', "jQuery('input#ProductAttribute_title').writetranslit('input#ProductAttribute_alias')");

?>
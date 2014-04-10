<?php if ( empty($all_attrs) ): ?>

	<?php echo TbHtml::alert(TbHtml::ALERT_COLOR_INFO, 'Список атрибутов пуст, '.CHtml::link('создать атрибут?', array('/admin/productAttribute/create'))) ?>

<?php else: ?>

	<?php echo TbHtml::alert(TbHtml::ALERT_COLOR_WARNING, 'Внимение! Удаление атрибута удалит этот атрибут и из дочерних категорий') ?>

	<div class="control-group">
		<div class="controls">
			<?php $this->widget('yiiwheels.widgets.select2.WhSelect2', array(
				'model' => $model,
				'attribute' => 'linkedAttrs',
				'name' => 'Category[check_attributes]',
				'data' => CHtml::listData($all_attrs, 'id', 'title'),
				'htmlOptions' => array(
					'multiple' => true,
					'class' => 'span6'
				),
			)) ?>
		</div>
	</div>

<?php endif ?>
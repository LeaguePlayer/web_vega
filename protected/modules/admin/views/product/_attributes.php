<div>
	<?php echo TbHtml::linkButton('Выбрать атрибуты', array(
		'class'=>'btn-block',
		'color'=>TbHtml::BUTTON_COLOR_INFO,
		'url'=>'#',
		'data-target'=>'#checkAttributesModal',
		'data-toggle'=>'modal'
	)); ?>
</div>

<table id="attributes-table" class="table">
	<tbody>
		<?php foreach ( $items as $item ): ?>
			<?php
				$htmlOptions = array('disabled' => $item['disabled']);
			?>
			<tr <?= ($item['disabled']) ? 'style="display:none;"' : '' ?>" data-id="<?= $item['model']->id ?>">
				<td>
					<?= CHtml::label($item['model']->title, 'ProductAttributeValue_'.$item['model']->id, array(
						'class' => 'control-label'
					)); ?>
				</td>
				<td>
					<?php
						$attribute_view = "_{$item['model']->field_type}_attribute";
						echo $this->renderPartial($attribute_view, array(
							'item' => $item,
							'htmlOptions' => $htmlOptions
						));
					?>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>


<?php $this->widget('bootstrap.widgets.TbModal', array(
	'id' => 'checkAttributesModal',
	'header' => 'Выбор атрибутов',
	'content' => $this->renderPartial('_check_attributes', array('items'=>$items), true),
	'footer' => array(
		TbHtml::button('Закрыть', array('data-dismiss' => 'modal')),
	),
	'fade'=>false,
	'backdrop'=>false
)); ?>

<?php

$script = <<< EOF
	var check_attr_modal = $('#checkAttributesModal');
	var attributes_table = $('#attributes-table');

	$('input:checkbox', check_attr_modal).click(function(e) {
		var self = $(this);
		var tr = $('tr[data-id='+self.data('id')+']');
		var input = $('[name^="ProductAttributeValue"]', tr);
		if ( self.prop('checked') ) {
			tr.show();
			input.removeAttr('disabled');
		} else {
			tr.hide();
			input.attr('disabled', 'disabled');
		}
	});
EOF;

Yii::app()->clientScript->registerScript('check', $script, CClientScript::POS_END);
?>
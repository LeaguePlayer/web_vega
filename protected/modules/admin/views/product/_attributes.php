
<table id="attributes-table" class="table">
	<tbody>
		<?php foreach ( $items as $item ): ?>
			<tr>
				<td>
					<?= CHtml::label($item['model']->title, 'ProductAttributeValue_'.$item['model']->id, array(
						'class' => 'control-label'
					)); ?>
				</td>
				<td>
					<?php
						$attribute_view = "_{$item['model']->field_type}_attribute";
						echo $this->renderPartial('admin.views.product.'.$attribute_view, array('item' => $item));
					?>
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
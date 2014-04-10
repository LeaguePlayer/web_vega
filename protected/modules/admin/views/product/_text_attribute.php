<?php
	$htmlOptions['rows'] = 3;
	echo CHtml::textArea("ProductAttributeValue[{$item['model']->id}]", $item['value']);
?>
<?php
	$htmlOptions['empty'] = '–';
	echo CHtml::hiddenField("ProductAttributeValue[{$item['model']->id}]", '', array('id'=>"hdProductAttributeValue_{$item['model']->id}"));
	echo CHtml::dropDownList("ProductAttributeValue[{$item['model']->id}]", $item['value'], $item['model']->decodeVariants(), array(
		'empty' => '–'
	));
?>
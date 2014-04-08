<?php
	$htmlOptions['empty'] = '–';
	echo CHtml::hiddenField("ProductAttributeValue[{$item['model']->id}]", '', array('id'=>"hdProductAttributeValue_{$item['model']->id}", 'disabled' => $htmlOptions['disabled']));
	echo CHtml::dropDownList("ProductAttributeValue[{$item['model']->id}]", $item['value'], $item['model']->decodeVariants(), $htmlOptions);
?>
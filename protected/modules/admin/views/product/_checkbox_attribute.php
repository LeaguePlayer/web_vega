<?php
	echo CHtml::hiddenField("ProductAttributeValue[{$item['model']->id}]", 0, array('id'=>"hdProductAttributeValue_{$item['model']->id}"));
	echo CHtml::checkBox("ProductAttributeValue[{$item['model']->id}]", $item['value']);
?>
<?php

foreach ( $items as $item ) {
	echo TbHtml::checkBoxControlGroup("CheckAttribute[{$item['model']->id}]", !$item['disabled'], array(
		'label' => $item['model']->title,
		'data-id' => $item['model']->id
	));
}

?>
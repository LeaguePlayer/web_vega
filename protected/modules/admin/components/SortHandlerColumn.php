<?php
/**
 * User: megakuzmitch
 * Date: 07.04.14
 * Time: 20:08
 */

Yii::import('zii.widgets.grid.CGridColumn');
class SortHandlerColumn extends CGridColumn
{
	public function renderDataCellContent($data, $row) {
		echo CHtml::tag('span', array('class' => 'handler'));
	}
}
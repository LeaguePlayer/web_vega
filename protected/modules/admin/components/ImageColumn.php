<?php

class ImageColumn extends CDataColumn
{
	public $filter = false;

	public $version = 'small';
	public $url;

	protected function renderDataCellContent($row,$data)
	{
		$image_name = CHtml::value($data, $this->name);
		if ( !$image_name )
			return;

		if ( $this->url ) {
			$url = $this->evaluateExpression($this->url, array('data'=>$data, 'row'=>$row));
		}

		$path = $data->getImageUrl($this->version);
		$image = CHtml::image($path, '');
		echo $url ? TbHtml::link($image, $url) : $image;
	}
}
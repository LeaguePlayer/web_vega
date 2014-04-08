<?php
	Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl().'/js/catalog.js', CClientScript::POS_END);

	$grid_view_params_expression = 'array(';
	if ( isset( $_GET['Product'] ) )
		$grid_view_params_expression .= '"Product" => $_GET["Product"]';
	if ( isset( $_GET['Product_page'] ) )
		$grid_view_params_expression .= ', "Product_page" => $_GET["Product_page"]';
	if ( isset( $_GET["Product_sort"] ) )
		$grid_view_params_expression .= ', "Product_sort" => $_GET["Product_sort"]';
	$grid_view_params_expression .= ')';

	$grid_params = $this->grabGridSearchParams('Product');
	$grid_view_params_expression = 'array(';
	$counter = 0;
	foreach ( $grid_params as $param => $value ) {
		if ( $counter > 0 )
			$grid_view_params_expression .= ', ';
		$counter++;
		$grid_view_params_expression .= '"'.$param.'" => $_GET["'.$param.'"]';
	}
	$grid_view_params_expression .= ')';
?>


<div class="container">

	<div class="row-fluid">

		<div class="span3">
			<div class="categories">
				<h3>Категории</h3>
				<?php $this->widget('admin.widgets.TbTreeView.TbTreeView', array(
					'data' => $categories,
					'openedId' => $open_category,
					'nodeContent' => 'TbHtml::link(TbHtml::icon("pencil"), array("/admin/category/update", "id" => $node["id"]))',
				)); ?>
			</div>

		</div>

		<div class="span9">
			<div class="products">
				<h3>Список товаров</h3>
				<?php $this->widget('bootstrap.widgets.TbGridView', array(
					'id' => 'product_grid',
					'dataProvider' => $productFinder->search(),
					'type' => TbHtml::GRID_TYPE_BORDERED,
					'filter' => $productFinder,
					'columns' => array(
						array(
							'name' => 'name',
							'type' => 'raw',
							'value' => 'CHtml::link($data->name, array("/admin/product/update", "id" => $data->id) + '.$grid_view_params_expression.')',
							'name' => 'name',
						),
						'category.name',
						array(
							'class' => 'bootstrap.widgets.TbButtonColumn',
							'template' => '{view}{update}',
							'buttons' => array(
								'update' => array(
									'url' => 'array("/admin/product/update", "id" => $data->id) + '.$grid_view_params_expression
								),
								'view' => array(
									'url' => '$data->url',
									'options' => array(
										'target' => '_blank'
									)
								)
							)
						)
					)
				)); ?>
			</div>
		</div>
	</div>
</div>
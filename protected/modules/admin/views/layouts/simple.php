<?php $this->beginContent('/layouts/main'); ?>

	<div class="container">
		<?php $this->widget('bootstrap.widgets.TbBreadcrumb', array(
			'links'=>$this->breadcrumbs,
			'homeUrl'=> '/admin'
		)); ?>

		<?php if ( !empty($this->menu) ): ?>
			<div class="row">
				<div class="well sidebar">
					<?php $this->widget('bootstrap.widgets.TbNav', array(
						'type' => TbHtml::NAV_TYPE_LIST,
						'items' => $this->menu
					)); ?>
				</div>
			</div>

		<?php endif ?>

		<?php echo $content; ?>

	</div>

<?php $this->endContent(); ?>
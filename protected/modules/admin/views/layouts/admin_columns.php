<?php $this->beginContent('/layouts/main'); ?>

    <div class="container">
        <div class="row-fluid">
            <div class="span9">
                <?php $this->widget('bootstrap.widgets.TbBreadcrumb', array(
                    'links'=>$this->breadcrumbs,
                    'homeUrl'=> '/admin'
                )); ?>
                <?php echo $content; ?>
            </div>
            <div class="span3">
                <?php if ( !empty($this->menu) ): ?>
                    <div class="well sidebar">
                        <?php $this->widget('bootstrap.widgets.TbNav', array(
                            'type' => TbHtml::NAV_TYPE_LIST,
                            'items' => $this->menu
                        )); ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>

<?php $this->endContent(); ?>
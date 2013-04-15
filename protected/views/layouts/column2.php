<?php $this->beginContent('//layouts/main'); ?>
<div class="row">
    <div class="small-12 large-3 push-9 columns">
        <div id="sidebar">
        <?php
            $this->beginWidget('zii.widgets.CPortlet', array(
                'title'=>'Menu',
            ));
            $this->widget('zii.widgets.CMenu', array(
                'items'=>$this->menu,
                'htmlOptions'=>array('class'=>'operations'),
            ));
            $this->endWidget();
        ?>
        </div><!-- sidebar -->
    </div>
    <div id="content" class="small-12 large-9 pull-3 columns">
        <?php echo $content; ?>
    </div><!-- content -->
</div>

<?php $this->endContent(); ?>

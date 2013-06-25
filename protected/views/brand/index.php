<?php $this->pageTitle = Site::TITLE . Yii::t('brand','Contributors'); ?>
<?php
/* @var $this BrandController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('brand','Contributors'),
);

/*$this->menu=array(
	array('label'=>'Create Brand', 'url'=>array('create')),
	array('label'=>'Manage Brand', 'url'=>array('admin')),
);*/
?>
<h1 class="no-space"><?php echo Yii::t('app','Contributors'); ?> <small>beta</small></h1>
<p><?php echo Yii::t('brand','Demos for Companies & Organizations'); ?></p>
<div class="row">
    <div class="small-6 large-4 columns">
        <?php echo CHtml::link(Yii::t('brand','Add your Organization'), array('create'),array('class' => 'button secondary')); ?>
    </div>
    <div class="small-6 columns">
        
    </div>
</div>
<div class="row">
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
</div>
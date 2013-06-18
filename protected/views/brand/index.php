<?php
/* @var $this BrandController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Brands',
);

/*$this->menu=array(
	array('label'=>'Create Brand', 'url'=>array('create')),
	array('label'=>'Manage Brand', 'url'=>array('admin')),
);*/
?>

<h1>Brands <small>beta</small></h1>
<div class="row">
    <div class="small-6 large-4 columns">
        <?php echo CHtml::link(Yii::t('app','Add your brand'), array('create'),array('class' => 'button secondary')); ?>
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
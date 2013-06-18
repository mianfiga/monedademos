<?php
/* @var $this BrandController */
/* @var $model Brand */

$this->breadcrumbs=array(
	'Brands'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Brand', 'url'=>array('index')),
	array('label'=>'View Brand', 'url'=>array('view', 'id'=>$model->id)),
    array('label' => 'Delete Brand', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => Yii::t('app','Are you sure you want to delete this?'),'csrf' => true)),
);
?>

<h1>Update Brand <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
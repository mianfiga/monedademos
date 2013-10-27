<?php
/* @var $this IslandController */
/* @var $model Island */

$this->breadcrumbs=array(
	'Islands'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Island', 'url'=>array('index')),
	array('label'=>'Create Island', 'url'=>array('create')),
	array('label'=>'View Island', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Island', 'url'=>array('admin')),
);
?>

<h1>Update Island <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
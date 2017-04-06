<?php
/* @var $this TribeController */
/* @var $model Tribe */

$this->breadcrumbs=array(
	'Tribes'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tribe', 'url'=>array('index')),
	array('label'=>'Create Tribe', 'url'=>array('create')),
	array('label'=>'View Tribe', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Tribe', 'url'=>array('admin')),
);
?>

<h1>Update Tribe <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

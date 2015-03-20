<?php
/* @var $this TribeController */
/* @var $model Tribe */

$this->breadcrumbs=array(
	'Tribes'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Tribe', 'url'=>array('index')),
	array('label'=>'Create Tribe', 'url'=>array('create')),
	array('label'=>'Update Tribe', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Tribe', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tribe', 'url'=>array('admin')),
);
?>

<h1>View Tribe #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nickname',
		'name',
		'email',
		'summary',
		'description',
		'image',
		'last_action',
		'group_id',
		'created_by',
		'added',
		'updated',
		'deleted',
	),
)); ?>

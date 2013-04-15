<?php
$this->breadcrumbs=array(
	'Invitations'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Create Invitation', 'url'=>array('create')),
	array('label'=>'List Invitation', 'url'=>array('index')),
);
?>

<h1>View Invitation #<?php echo $model->id; ?></h1>

<?php 

$route = 'user/invited';
$params = array('id'=>$model->id, 'code'=>$model->code);

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'note',
		array(
				'label' => 'Share Link',
				'type'  => 'raw',
				'value' => CHtml::link($this->createAbsoluteUrl($route,$params), array_merge(array($route),$params))
			),
		array(
				'label' => 'Used',
				'type'  => 'raw',
				'value' => ($model->used?'Yes':'No')
			),
	),
)); ?>

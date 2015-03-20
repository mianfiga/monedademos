<?php
/* @var $this TribeController */
/* @var $model Tribe */

$this->breadcrumbs=array(
	'Tribes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tribe', 'url'=>array('index')),
	array('label'=>'Manage Tribe', 'url'=>array('admin')),
);
?>

<h1>Create Tribe</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this IslandController */
/* @var $model Island */

$this->breadcrumbs=array(
	'Islands'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Island', 'url'=>array('index')),
	array('label'=>'Manage Island', 'url'=>array('admin')),
);
?>

<h1>Create Island</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
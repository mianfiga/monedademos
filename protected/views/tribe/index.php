<?php
/* @var $this TribeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tribes',
);

$this->menu=array(
	array('label'=>'Create Tribe', 'url'=>array('create')),
	array('label'=>'Manage Tribe', 'url'=>array('admin')),
);
?>

<h1>Tribes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

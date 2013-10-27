<?php
/* @var $this IslandController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Islands',
);

$this->menu=array(
	array('label'=>'Create Island', 'url'=>array('create')),
	array('label'=>'Manage Island', 'url'=>array('admin')),
);
?>

<h1>Islands</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

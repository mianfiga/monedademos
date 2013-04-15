<?php
$this->breadcrumbs=array(
	'Invitations',
);

$this->menu=array(
	array('label'=>'Create Invitation', 'url'=>array('create')),
	array('label'=>'List Invitation', 'url'=>array('index')),
);
?>

<h1>Invitations</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

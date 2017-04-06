<?php
/* @var $this TribeController */
/* @var $model TribeMigration */
$tribe = $model->tribe;
$this->breadcrumbs=array(
	'Tribes'=> array('index'),
	$tribe->nickname => array('view', 'id' => $model->to_id),
	'Migration',
);

$this->menu=array(
	array('label'=>'List Tribe', 'url'=>array('index')),
	array('label'=>'Manage Tribe', 'url'=>array('admin')),
);
?>

<h1>Únete a la tribu: <span class="subheader"><?= $tribe->name?></span></h1>

<?php echo $this->renderPartial('_formMigrationRequest', array('model'=>$model)); ?>

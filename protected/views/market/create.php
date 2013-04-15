<?php
$this->breadcrumbs=array(
	'Market'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Market Ad', 'url'=>array('index')),
);
?>

<h1><?php echo Yii::t('market','New Advertisement') ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

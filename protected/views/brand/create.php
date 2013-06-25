<?php
/* @var $this BrandController */
/* @var $model Brand */

$this->breadcrumbs=array(
	Yii::t('brand','Contributors') => array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Brand', 'url'=>array('index')),
	array('label'=>'Manage Brand', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('brand','Add Contributor')?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
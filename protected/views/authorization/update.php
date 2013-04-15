<?php
$this->breadcrumbs=array(
	Yii::t('app','Account') => array('transaction/index'),
//	$model->title=>array('view','id'=>$model->getAccountNumber()),
	Yii::t('app','Edit account'),
);

$this->menu=array(
	array('label'=>'List Account', 'url'=>array('index')),
	array('label'=>'Create Account', 'url'=>array('create')),
	array('label'=>'View Account', 'url'=>array('view', 'id'=>$model->getAccountNumber())),
	array('label'=>'Manage Account', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Edit account')?> <span class="subheader"><?php echo $model->getAccountNumber()?></span></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

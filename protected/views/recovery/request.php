<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
);

?>

<h1><?php echo Yii::t('app','Password recovery'); ?></h1>

<?php echo $this->renderPartial('//recovery/_form', array('model'=>$model)); ?>

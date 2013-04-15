<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create',
);
if(!Yii::app()->user->isGuest)
{
	$this->menu=array(
		array('label'=>'Invite Friend', 'url'=>array('invitation/create')),
		array('label'=>'View Invitations', 'url'=>array('invitation/index')),
		/*	array('label'=>'Manage User', 'url'=>array('admin')),*/
	);
}
?>
<h1><?php echo Yii::t('app','New to Demos?') ?> <span class="subheader"><?php echo Yii::t('app','Sign up') ?></span></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

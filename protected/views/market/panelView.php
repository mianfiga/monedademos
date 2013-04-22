<?php
$this->breadcrumbs=array(
	'Market'=>array('index'),
	$ad->title =>array('view', 'id'=>$ad->id),
	'Panel'=>array('panel', 'id'=>$ad->id),
	'View & Update user'
);

$this->menu=array(
	array('label'=> Yii::t('market','List Advertisements'), 'url'=>array('index')),
	array('label'=> Yii::t('market','Create Advertisement'), 'url'=>array('create')),
	/*array('label'=>'Manage Transaction', 'url'=>array('admin')),*/
);
?>

<h1><?php echo Yii::t('app','View & Update {name}\'s participation in "{title}"', array('{name}' => $entity->name,'{title}' => $ad->title)) ?></h1>

<?php echo $this->renderPartial('_panelForm', array('model' => $joined)); ?>

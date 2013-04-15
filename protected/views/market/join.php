<?php
$this->breadcrumbs=array(
	'Market'=>array('index'),
	Yii::t('market','Join'),
);

$this->menu=array(
	array('label'=> Yii::t('app','List Market'), 'url'=>array('index')),
	array('label'=>'Create MarketAd', 'url'=>array('create')),
	/*array('label'=>'Manage Transaction', 'url'=>array('admin')),*/
);
?>

<h1><?php echo Yii::t('market','Join to "{title}"', array('{title}' => $ad->title))?></h1>

<?php echo $this->renderPartial('_joinForm', array('model'=>$model)); ?>

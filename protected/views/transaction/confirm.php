<?php
$this->breadcrumbs=array(
	'Transactions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=> Yii::t('app','List Transactions'), 'url'=>array('list')),
	/*array('label'=>'Manage Transaction', 'url'=>array('admin')),*/
);
?>

<h1>Create Transaction (<?php echo $action ?>)</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,
				'charge_accounts'=> $charge_accounts,
				'deposit_accounts'=> $deposit_accounts)); ?>

<h2><?php echo Yii::t('app','Client contribution')?></h2>
<?php
	$client = Authorization::splitAccountNumber($charge_accounts);
	echo $this->renderPartial('contribution/_view', array('data'=>User::model()->findByPk($client['user_id'])));
?>

<h2><?php echo Yii::t('app','Vendor contribution')?></h2>
<?php
	$vendor = Authorization::splitAccountNumber($deposit_accounts);
	echo $this->renderPartial('contribution/_view', array('data'=>User::model()->findByPk($vendor['user_id'])));
?>


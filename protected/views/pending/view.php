<?php

$this->breadcrumbs=array(
	'Transactions'=>array('index'),
	($model!=null?$model->id:null),
);

$this->menu=array(
	array('label'=> Yii::t('app','Select Account'), 'url'=>array('Transaction/index')),
	array('label'=> Yii::t('app','New Transaction'), 'url'=>array('Transaction/transfer')),
	array('label'=> Yii::t('app','New Charge'), 'url'=>array('Transaction/charge')),
	array('label'=> Yii::t('app','New Movement'), 'url'=>array('Transaction/movement')),
	array('label'=> Yii::t('app','List Transactions'), 'url'=>array('Transaction/list')),
	array('label'=> Yii::t('app','List Pending'), 'url'=>array('Pending/index')),
/*	array('label'=>'Manage Transaction', 'url'=>array('admin')),*/
);
?>
<?php

	if($model==null)
	{?>
<h1>Pending Transaction not accessible</h1>
<?php
		if($charge_errors!=0)
			echo "Errors found in the charge (source) account."."<br/>";
		if($deposit_errors!=0)
			echo "Errors found in the deposit (destination) account."."<br/>";
	}
	else
	{
?>
<h1><?php echo Yii::t('app','Pending Transaction')?> #<?php echo $model->id; ?></h1>

<?php
	$attr = array(
						'id',
						array(
  		          'label'=>Yii::t('app','Date'),
  		          'type'=>'raw',
  		          'value'=> date('d-m-Y H:m:i',Transaction::convertDatetime($model->executed_at))
							),
						'class',
						array(
  		          'label'=>Yii::t('app','Amount'),
  		          'type'=>'raw',
  	      	    'value'=>CHtml::encode(Transaction::amountSystemToUser($model->amount))
							),
						array(
  		          'label'=>Yii::t('app','Charge account (source)'),
  		          'type'=>'raw',
  		          'value'=>CHtml::encode($model->charge_user.'.'.$model->charge_account.'.*')
							),
						array(
  		          'label'=>Yii::t('app','Deposit account (destination)'),
  		          'type'=>'raw',
  		          'value'=>CHtml::encode($model->deposit_user.'.'.$model->deposit_account.'.*')
							),
						'subject',
					);

	if(Yii::app()->user->getId() == $model->charge_user)
	{
		array_push ($attr,array(
					  		          'label'=>Yii::t('app','Confirm payment'),
  		    					      'type'=>'raw',
					  		          'value'=>CHtml::link(CHtml::htmlButton(Yii::t('app','Checkout')), array('pending/checkout','id'=>$model->id)),
												));
	}

		$this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=> $attr,
		));
	}
?>

<?php echo CHtml::link(CHtml::htmlButton('Pending List'), array('pending/index')); ?> 
<?php echo CHtml::link(CHtml::htmlButton('New Operation'), array('transaction/index')); ?>


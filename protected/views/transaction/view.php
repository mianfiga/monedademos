<?php

$this->breadcrumbs=array(
	'Transactions'=>array('index'),
	($model!=null?$model->id:null),
);

$this->menu=array(
	array('label'=> Yii::t('app','Select Account'), 'url'=>array('index')),
	array('label'=> Yii::t('app','New Transaction'), 'url'=>array('transfer')),
	array('label'=> Yii::t('app','New Charge'), 'url'=>array('charge')),
	array('label'=> Yii::t('app','New Movement'), 'url'=>array('movement')),
	array('label'=> Yii::t('app','List Transactions'), 'url'=>array('list')),
	array('label'=> Yii::t('app','List Pending'), 'url'=>array('Pending/index')),
/*	array('label'=>'Manage Transaction', 'url'=>array('admin')),*/
);
?>
<?php

	if($model==null)
	{?>
<h1><?php echo (($charge_errors|$deposit_errors)!=0?'Transaction NOT completed':'Transaction not accessible')?></h1>
<?php
		if($charge_errors!=0)
			echo "Errors found in the charge (source) account."."<br/>";
		if($deposit_errors!=0)
			echo "Errors found in the deposit (destination) account."."<br/>";
	}
	else
	{
?>
<h1>View Transaction #<?php echo $model->id; ?></h1>

<?php
		$this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=>array(
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
            'value'=>CHtml::encode($model->getAmount())
					),
				array(
            'label'=>Yii::t('app','Charge Account (Source)'),
            'type'=>'raw',
            'value'=>CHtml::encode($model->getChargeAccountNumber()) .'; '. $model->chargeEntity->name .' '. $model->chargeEntity->surname
					),
				array(
            'label'=>Yii::t('app','Deposit Account (Destination)'),
            'type'=>'raw',
            'value'=>CHtml::encode($model->getDepositAccountNumber()) .'; '. $model->depositEntity->name .' '. $model->depositEntity->surname
					),
				'subject',
			),
		));
	}
?>
<br/>
<?php echo CHtml::link(Yii::t('app','View movements'), array('transaction/list'),array('class' => 'button')); ?> 
<?php echo CHtml::link(Yii::t('app','Back to Account'), array('transaction/index'),array('class' => 'button secondary')); ?>
<br /><br />
<?php $this->renderPartial('/contribution/_contributions', array('model'=> $model)); ?>




<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('executed_at')); ?>:</b>
	<?php echo CHtml::encode(date('d-m-Y H:m:i',Transaction::convertDatetime($data->executed_at))); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('class')); ?>:</b>
	<?php echo CHtml::encode($data->class); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('amount')); ?>:</b>
	<?php echo CHtml::encode(Transaction::amountSystemToUser($data->amount)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('charge_account')); ?>:</b>
	<?php echo CHtml::encode($data->charge_user.'.'.$data->charge_account.'.*'); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deposit_account')); ?>:</b>
	<?php echo CHtml::encode($data->deposit_user.'.'.$data->deposit_account.'.*'); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('subject')); ?>:</b>
	<?php echo CHtml::encode($data->subject); ?>
	<br />

<?php if(Yii::app()->user->getId() == $data->charge_user)
			{?>

	<b><?php echo CHtml::encode(Yii::t('app','Confirm payment')); ?>:</b>
	<?php echo CHtml::link(CHtml::htmlButton(Yii::t('app','Checkout')), array('pending/checkout','id'=> $data->id)); ?>
<?php	}?>
	<br />
</div>

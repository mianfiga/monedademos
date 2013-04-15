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
	<?php echo CHtml::encode($data->getAmount()); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('charge_account')); ?>:</b>
	<?php echo CHtml::encode($data->charge_account_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deposit_account')); ?>:</b>
	<?php echo CHtml::encode($data->deposit_account_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('subject')); ?>:</b>
	<?php echo CHtml::encode($data->subject); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('deposit_user')); ?>:</b>
	<?php echo CHtml::encode($data->deposit_user); ?>
	<br />
	*/ ?>

</div>

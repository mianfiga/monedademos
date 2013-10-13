<div class="view">
	<?php echo CHtml::link(Yii::t('app', 'Send e-mail'), array('contribution/contact', 'id' => $data->id), array('class' => 'button secondary tiny contribution_button')) ?>
	<b><?php echo CHtml::encode($data->getAttributeLabel('contribution_title')); ?>:</b>
	<?php echo CHtml::encode($data->contribution_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contribution_text')); ?>:</b>
	<?php echo CHtml::encode($data->contribution_text); ?>
</div>



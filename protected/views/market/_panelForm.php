<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'market-joined-join-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div>
		<?php echo $model->comment?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'form_comment'); ?>
		<?php echo $form->textArea($model,'form_comment',array('rows'=>4, 'cols'=>50)); ?>
		<?php echo $form->error($model,'form_comment'); ?>
	</div>

<?php /*	<div class="row">
		<?php //echo $form->labelEx($model,'email_comment'); ?>
		<?php echo $form->checkBox($model,'email_comment'); ?>
		Check this to send a copy of this comment by e-email.
		<?php echo $form->error($model,'email_commnet'); ?> 
	</div>*/?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status', MarketJoined::statusList()); ?>
		<?php echo $form->error($model,'email_commnet'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Update'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'transaction-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo $form->textField($model,'amount',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'charge_account_number'); ?>
		<?php if (count($charge_accounts)>0)
						echo $form->dropDownList($model,'charge_account_number',$charge_accounts);
					else
						echo $form->textField($model,'charge_account_number',array('size'=>23,'maxlength'=>23)); ?>
		<?php echo $form->error($model,'charge_account_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deposit_account_number'); ?>
		<?php if (count($deposit_accounts)>0)
						echo $form->dropDownList($model,'deposit_account_number',$deposit_accounts);
					else
						echo $form->textField($model,'deposit_account_number',array('size'=>23,'maxlength'=>23)); ?>
		<?php echo $form->error($model,'deposit_account_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

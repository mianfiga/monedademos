<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'transaction-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php /*echo $form->errorSummary($model); */ ?>

	<div class="row">
		<?php echo $form->labelEx($model,'form_amount'); ?>
		<?php echo $form->textField($model,'form_amount',array('size'=>10,'maxlength'=>10)); ?> <b>đ</b><br/>(Examples: 12 12.03 or 12.30)
		<?php echo $form->error($model,'form_amount'); ?>
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
		<?php echo $form->textField($model,'subject',array('size'=>23,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>

	<div class="row buttons">
		<?php echo $form->hiddenField($model,'sid'); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

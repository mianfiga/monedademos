<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'invitation-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'note'); ?>
		<?php echo $form->textField($model,'note',array('size'=>10,'maxlength'=>127)); ?>
		<?php echo $form->error($model,'note'); ?>
	</div>
<?php /*
	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sent'); ?>
		<?php echo $form->textField($model,'sent'); ?>
		<?php echo $form->error($model,'sent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'used'); ?>
		<?php echo $form->textField($model,'used'); ?>
		<?php echo $form->error($model,'used'); ?>
	</div>
*/ ?>
	<div class="row buttons">
		<?php /*echo $form->hiddenField($model,'code'); */ ?>
		<?php echo CHtml::submitButton('Continue'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

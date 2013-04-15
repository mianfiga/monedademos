<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'market-joined-join-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div>
		<?php echo $model->comment?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'form_comment'); ?>
		<?php echo $form->textArea($model,'form_comment',array('rows'=>4, 'cols'=>50)); ?>
		<?php echo $form->error($model,'form_comment'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'show_mail'); ?>
		<?php echo $form->checkBox($model,'show_mail'); ?>
		<?php echo $form->error($model,'show_mail'); ?>
		<?php Yii::t('market','Uncheck this field in case you don\'t want to share your e-mail account with the person that created this advertisement'); ?>.
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Join'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

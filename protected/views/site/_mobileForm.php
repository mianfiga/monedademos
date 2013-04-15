<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'mobile-form',
	'action'=>CHtml::normalizeUrl(array('site/mobile')),
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
	<div class="row">
		<?php echo $form->hiddenField($model,'url'); ?>
		<?php echo $form->labelEx($model,'mobile'); ?><?php echo $form->checkBox($model,'mobile'); ?>
		<?php echo CHtml::submitButton('change'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

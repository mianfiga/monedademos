<?php
/* @var $this LinkController */
/* @var $model Link */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'link-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
        <div class="small-4 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model,'url'); ?>
                <?php echo $form->textField($model,'url',array('size'=>10)); ?>
                <?php echo $form->error($model,'url'); ?>
            </div>
        </div>
	</div>
    <div class="row">
        <div class="small-12 columns">
        	<div class="form_row">
                <?php echo $form->labelEx($model,'text'); ?>
                <?php echo $form->textField($model,'text',array('size'=>60,'maxlength'=>255)); ?>
                <?php echo $form->error($model,'text'); ?>
            </div>
        </div>
    </div>
        

	<div class="row">
		<?php echo $form->labelEx($model,'logo'); ?>
		<?php echo $form->textField($model,'logo',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'logo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'public'); ?>
		<?php echo $form->textField($model,'public'); ?>
		<?php echo $form->error($model,'public'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<?php /*
	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>20,'maxlength'=>20)); ?>
	</div>
*/?>
	<div class="row">
			<div class="small-12 columns">
					<div class="form_row">
						<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>127)); ?>
					</div>
			</div>
	</div>

	<?php /*
	<div class="row">
		<?php echo $form->label($model,'class'); ?>
		<?php echo $form->textField($model,'class',array('size'=>7,'maxlength'=>7)); ?>
	</div>
	*/?>
	<?php /*
	<div class="row">
		<?php echo $form->label($model,'type'); ?>
		<?php echo $form->textField($model,'type',array('size'=>11,'maxlength'=>11)); ?>
	</div>
	*/?>
	<?php /*
	<div class="row">
		<?php echo $form->label($model,'summary'); ?>
		<?php echo $form->textArea($model,'summary',array('rows'=>6, 'cols'=>50)); ?>
	</div>
	*/?>
	<?php /*
	<div class="row">
		<?php echo $form->label($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>20,'maxlength'=>20)); ?>
	</div>
	*/?>
	<?php /*
	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
	</div>
	*/?>
	<?php /*
	<div class="row">
		<?php echo $form->label($model,'image'); ?>
		<?php echo $form->textField($model,'image',array('size'=>60,'maxlength'=>254)); ?>
	</div>
	*/?>
	<?php /*
	<div class="row">
		<?php echo $form->label($model,'mailmode'); ?>
		<?php echo $form->textField($model,'mailmode',array('size'=>9,'maxlength'=>9)); ?>
	</div>
	*/?>
	<?php /*
	<div class="row">
		<?php echo $form->label($model,'visible'); ?>
		<?php echo $form->textField($model,'visible'); ?>
	</div>
	*/?>
	<?php /*
	<div class="row">
		<?php echo $form->label($model,'expiration'); ?>
		<?php echo $form->textField($model,'expiration'); ?>
	</div>
	*/?>
	<?php /*
	<div class="row">
		<?php echo $form->label($model,'created_by'); ?>
		<?php echo $form->textField($model,'created_by',array('size'=>10,'maxlength'=>10)); ?>
	</div>
	*/?>
	<?php /*
	<div class="row">
		<?php echo $form->label($model,'added'); ?>
		<?php echo $form->textField($model,'added'); ?>
	</div>
	*/?>
	<?php /*
	<div class="row">
		<?php echo $form->label($model,'updated'); ?>
		<?php echo $form->textField($model,'updated'); ?>
	</div>
	*/?>
	<div class="row">
			<div class="small-12 columns">
					<div class="form_row">
						<?php echo CHtml::submitButton(Yii::t('app', 'Search')); ?>
					</div>
			</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->

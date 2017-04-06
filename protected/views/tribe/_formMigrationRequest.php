<?php
/* @var $this TribeController */
/* @var $model Tribe */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tribeMigration-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'to_id'); ?>

	<div class="row">
		<div class="small-10 large-8 columns form_row buttons small-centered" style="text-align: justify;">
			<p>Estas a punto de solicitar cambiar de tribu.
				Una vez completada esta solicitud los administradores de la tribu
				estudiaran tu solicitud.Si cumples con los requisitos que hayan
				establecido desde esta tribu y tu solicitud es atendida cambiaras de
				tribu con la entrada del próximo mes.
			</p>
			<p>Sólo puedes solicitar cambiar de tribu una vez al mes. Para evitar
				problemas te recomendamos que contactes con los miembros de la tribu y
				participes en sus actividades antes de solicitar el cambio.
			</p>
			<p>Pues bien, una vez dicho todo esto si quieres ya puedes continuar:
			</p>
		</div>
	</div>

	<div class="row">
		<div class="small-10 large-8 columns form_row buttons small-centered">
				<?php echo CHtml::submitButton(Yii::t('tribe', 'Request migration'), array('class' => 'button large expand', 'onclick' => '$("#procesingmodal").foundation(\'reveal\', \'open\');')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<div id="procesingmodal" class="reveal-modal">
  <h2><?php echo Yii::t('app','Processing') ?></h2>
  <p><?php echo Yii::t('app','It will take just a moment, wait please') ?>.</p>
</div>

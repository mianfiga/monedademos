<?php
/* @var $this RateController */
/* @var $model Rate */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'rate-form',
        'action' => CHtml::normalizeUrl(array('rate/create', 'sid'=>$model->sid)),
        'enableAjaxValidation' => false,
            ));
    ?>

        <?php echo $form->errorSummary($model); ?>

    <div class="form_row">
        <?php echo $form->labelEx($model, 'puntuation'); ?>
        <?php
        $this->widget('CStarRating', array(
            'model' => $model,
            'attribute' => 'puntuation',
            'minRating' => 1,
            'maxRating' => 5,
            'starCount' => 5,
            'resetText' => '0',
            'resetValue' => 0,
            'readOnly' => false,
        ));
        ?><br/>
<?php echo $form->error($model, 'puntuation'); ?>
    </div>

    <div class="form_row">
        <?php echo $form->labelEx($model, 'comment'); ?>
<?php echo $form->textArea($model, 'comment', array('rows' => 6, 'cols' => 50)); ?>
<?php echo $form->error($model, 'comment'); ?>
    </div>

    <div class="form_row buttons">
    <?php echo $form->hiddenField($model, 'url'); ?>
    <?php echo CHtml::submitButton(Yii::t('app','Send rate'),array('class' => 'button')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
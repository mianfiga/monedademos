<?php
/* @var $this BrandController */
/* @var $model Brand */
/* @var $form CActiveForm */

//Reveal javascript
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/vendor/custom.modernizr.js');
$cs->registerScriptFile($baseUrl . '/js/foundation.min.js');
$cs->registerScriptFile($baseUrl . '/js/vendor/custom.modernizr.js');
$cs->registerScript('foundation_reveal', '$(document).foundation(\'reveal\', { closeOnBackgroundClick: false, close: function(){return false;}});', CClientScript::POS_READY);
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'brand-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="small-12 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'name'); ?>
                <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 127)); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="small-12 large-7 columns form_row">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns form_row">
                <?php echo $form->labelEx($model, 'form_image'); ?>
                <?php echo $form->fileField($model, 'form_image', array('maxlength' => 254)); ?>
                <?php echo $form->error($model, 'form_image'); ?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="small-12 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'summary'); ?>
                <?php echo $form->textArea($model, 'summary', array('rows' => 6, 'cols' => 50)); ?>
                <?php echo $form->error($model, 'summary'); ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="small-12 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'description'); ?>
                <?php echo $form->textArea($model, 'description', array('rows' => 6, 'cols' => 50)); ?>
                <?php echo $form->error($model, 'description'); ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="small-12 columns">
            <div class="form_row buttons">
                <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), array('class' => 'button large', 'onclick' => '$("#procesingmodal").foundation(\'reveal\', \'open\');')); ?>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<div id="procesingmodal" class="reveal-modal">
    <h2><?php echo Yii::t('app', 'Processing') ?></h2>
    <p><?php echo Yii::t('app', 'It will take just a moment, wait please') ?>.</p>
</div>
<?php
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
        'id' => 'recovery-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required') ?>.</p>
    <div class="row">
        <div class="small-centered small-10 large-5 columns form_row">
            <?php echo $form->errorSummary($model); ?>
        </div>
    </div>
    <div class="row">
        <div class="small-centered small-10 large-5 columns form_row">
            <?php echo $form->labelEx($model, 'username'); ?>
            <?php echo $form->textField($model, 'username', array('size' => 60, 'maxlength' => 128)); ?>
            <?php echo $form->error($model, 'username'); ?>
        </div>
    </div><br/>

    <?php if (CCaptcha::checkRequirements()): ?>
        <div class="row">
            <div class="small-centered small-7 large-5 columns form_row">
                <?php echo $form->labelEx($model, 'verifyCode'); ?>
                <div>
                    <?php $this->widget('CCaptcha'); ?><br/>
                    <?php echo $form->textField($model, 'verifyCode'); ?>
                </div>
                <div class="hint">Please enter the letters as they are shown in the image above.
                    <br/>Letters are not case-sensitive.</div>
                <?php echo $form->error($model, 'verifyCode'); ?>
            </div>
        </div>
    <?php endif; ?>
    <br/><br/>
    <div class="row">
        <div class="small-centered small-10 large-8 columns form_row buttons small-centered">
            <?php echo CHtml::submitButton(Yii::t('app', 'Request a new password'), array('class' => 'button large expand', 'onclick' => '$("#procesingmodal").foundation(\'reveal\', \'open\');')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<div id="procesingmodal" class="reveal-modal">
    <h2><?php echo Yii::t('app', 'Processing') ?></h2>
    <p><?php echo Yii::t('app', 'It will take just a moment, wait please') ?>.</p>
</div>

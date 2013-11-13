<?php
//Reveal javascript
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/vendor/custom.modernizr.js');
$cs->registerScriptFile($baseUrl . '/js/foundation.min.js');
$cs->registerScriptFile($baseUrl . '/js/vendor/custom.modernizr.js');
$cs->registerScript('foundation_reveal', '$(document).foundation(\'reveal\', { closeOnBackgroundClick: false, close: function(){return false;}});', CClientScript::POS_READY);


$opmodel = Yii::app()->session['operations'][$model->sid]['model'];
?>
<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'transaction-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <div class="small-11 large-6 small-centered columns confirm_box">
             <ul class="pricing-table">
                <li class="title"><?php echo Yii::t('app', 'Transaction summary') ?></li>
                <li class="price"><?php echo $opmodel->getAmount() ?></li>
                <li class="bullet-item"><?php echo Yii::t('app', 'From account') ?>: <strong><?php echo $opmodel->getChargeAccountNumber() ?></strong></li>
                <li class="bullet-item"><?php echo Yii::t('app', 'To account') ?>: <strong><?php echo $opmodel->getDepositAccountNumber() ?></strong></li>
                <li class="cta-button">
                    <div class="form_row">
                        <?php echo $form->labelEx($model, 'password'); ?>
                        <?php echo $form->passwordField($model, 'password', array('size' => 10, 'maxlength' => 128,
                                    'placeholder' => Yii::t('app','{account_number} pin/password',
                                            array('{account_number}' =>$opmodel->getChargeAccountNumber())),
                                    'autofocus' => 'autofocus')); ?>
                        <?php echo $form->error($model, 'password'); ?>
                    </div>

                    <div class="form_row buttons">
                        <?php echo $form->hiddenField($model, 'sid'); ?>
                        <?php echo CHtml::submitButton(Yii::t('app', 'Confirm'),
                                array('class' => 'button large expand',
                                    'onclick' => '$("#procesingmodal").foundation(\'reveal\', \'open\');')); ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'pretransaction-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <div class="form_row buttons">
        <?php echo $form->hiddenField($model2, 'sid'); ?>
        <?php
        $isChargeEntity = Yii::app()->user->getId() == Yii::app()->session['operations'][$model->sid]['model']->charge_entity;
        ?>
        <?php echo CHtml::submitButton($isChargeEntity ? Yii::t('app', 'Confirm Later, add to pending transaction') : Yii::t('app', 'Send to Client as pending transaction'), array('class' => 'button secondary', 'onclick' => '$("#procesingmodal").foundation(\'reveal\', \'open\');')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<div id="procesingmodal" class="reveal-modal">
  <h2><?php echo Yii::t('app','Processing') ?></h2>
  <p><?php echo Yii::t('app','It will take just a moment, wait please') ?>.</p>
</div>
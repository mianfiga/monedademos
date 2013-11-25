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
        'id' => 'user-form',
//        'action' => CHtml::normalizeUrl(array('user/create')),
        'enableAjaxValidation' => false,
            ));

    $scenario = $model->getScenario();
    ?>

    <p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required') ?>.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php if ($scenario == 'register' || $scenario == 'update') { ?>
        <div class="row">
            <div class="small-12 large-5 columns">
                <div class="form_row">
                    <?php echo $form->labelEx($model, 'name'); ?>
                    <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 127)); ?>
                    <?php echo $form->error($model, 'name'); ?>
                </div>
            </div>
            <div class="small-12 large-7 columns">
                <div class="form_row">
                    <?php echo $form->labelEx($model, 'surname'); ?>
                    <?php echo $form->textField($model, 'surname', array('size' => 60, 'maxlength' => 255)); ?>
                    <?php echo $form->error($model, 'surname'); ?>
                </div>
            </div>
        </div>
        <br/><br/>
    <?php } ?>

    <?php if ($scenario == 'register' || $scenario == 'update') { ?>
        <div class="row">
            <?php if ($scenario == 'register') { ?>
                <div class="small-10 large-5 columns form_row">
                    <?php echo $form->labelEx($model, 'username'); ?>
                    <?php echo $form->textField($model, 'username', array('size' => 60, 'maxlength' => 128)); ?>
                    <?php echo $form->error($model, 'username'); ?>
                </div>
            <?php } ?>

            <div class="small-12 large-7 columns form_row">
                <?php echo $form->labelEx($model, 'email'); ?>
                <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'email'); ?>
            </div>
        </div>
    <?php } ?>

    <br/>
    <div class="row">
        <?php if ($scenario == 'register' || $scenario == 'update' || $scenario == 'recovery') { ?>
            <div class="small-10 large-6 columns form_row">
                <?php echo $form->labelEx($model, 'plain_password'); ?>
                <?php echo $form->passwordField($model, 'plain_password', array('size' => 60, 'maxlength' => 128)); ?>
                <?php echo $form->error($model, 'plain_password'); ?>
            </div>
        <?php } ?>

        <?php if ($scenario == 'register' || $scenario == 'update' || $scenario == 'recovery') { ?>
            <div class="small-10 large-6 columns form_row">
                <?php echo $form->labelEx($model, 'password2'); ?>
                <?php echo $form->passwordField($model, 'password2', array('size' => 60, 'maxlength' => 128)); ?>
                <?php echo $form->error($model, 'password2'); ?>
            </div>
        <?php } ?>
    </div>
    <br/><br/>

    <?php if ($scenario == 'update') { ?>
        <div class="row">
            <div class="small-10 large-5 columns form_row">
                <?php echo $form->labelEx($model, 'birthday'); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'language' => 'es',
                    'model' => $model, // Model object
                    'attribute' => 'birthday', // Attribute name
                    'options' => array(
//									'showOn'=>'button',
//									'showButtonPanel'=>true,
                        'yearRange' => date('Y') - 110 . ':' . date('Y'),
                        'defaultDate' => '-25y',
                        'changeMonth' => true,
                        'changeYear' => true,
                        'dateFormat' => 'yy-mm-dd',
//									'buttonText'=>'',
                    ), // jquery plugin options
                    'htmlOptions' => array('readonly' => true) // HTML options
                ));
                ?> 
                <?php echo $form->error($model, 'birthday'); ?>
            </div>
        </div>
        <br/><br/>
    <?php } ?>

    <?php if ($scenario == '!') { ?>
        <div class="row">
            <div class="small-12 large-7 columns form_row">

                <?php echo $form->labelEx($model, 'identification'); ?>
                <?php /* $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                  'model'=>$model,																	// Model object
                  'attribute'=>'identification_method',													// Attribute name
                  'source'=>array('DNI', 'Passport', 'ac3'),
                  // additional javascript options for the autocomplete plugin
                  'options'=>array(
                  'minLength'=>'1',
                  ),
                  'htmlOptions'=>array(
                  'style'=>'height:20px;'
                  ),
                  )); */ ?>
                <?php echo $form->dropDownList($model, 'identification_method', User::identificationList(), array('width' => '50px')); ?> 
                <?php echo $form->textField($model, 'identification_number', array('size' => 15, 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'identification_method'); ?>
                <?php echo $form->error($model, 'identification_number'); ?>
            </div>
        </div>
    <?php } ?>

    <?php if ($scenario == 'register' || $scenario == 'edit') { ?>
        <div class="row">
            <div class="small-12 large-12 columns form_row">
                <?php echo $form->labelEx($model, 'contribution_title'); ?>
                <?php echo $form->textField($model, 'contribution_title', array('size' => 60, 'maxlength' => 255, 'placeholder' => Yii::t('app', 'A brief of what you can offer to other people'))); ?>
                <?php echo $form->error($model, 'contribution_title'); ?>
            </div>
        </div>
    <?php } ?>

    <?php if ($scenario == 'register' || $scenario == 'edit') { ?>
        <div class="row">
            <div class="small-12 large-12 columns form_row">
                <?php echo $form->labelEx($model, 'contribution_text'); ?>
                <?php echo $form->textArea($model, 'contribution_text', array('rows' => 7, 'cols' => 50, 'placeholder' => Yii::t('app', 'So now tell us what you can offer to other people in detail'))); ?>
                <?php echo $form->error($model, 'contribution_text'); ?>
            </div>
        </div>
    <?php } ?>

    <?php if ($scenario == 'edit') { ?>
        <div class="row">
            <div class="small-12 large-7 columns form_row">
                <?php echo $form->labelEx($model, 'contact'); ?>
                <?php echo $form->textArea($model, 'contact', array('rows' => 6, 'cols' => 50)); ?>
                <?php echo $form->error($model, 'contact'); ?>
            </div>
        </div>
    <?php } ?>

    <?php if ($scenario == 'register' || $scenario == 'edit') { ?>
        <br/>
        <div class="row">
            <div class="small-7 large-4 columns form_row">
                <?php echo $form->labelEx($model, 'zip'); ?>
                <?php echo $form->textField($model, 'zip', array('size' => 16, 'maxlength' => 16)); ?>
                <?php echo $form->error($model, 'zip'); ?>
            </div>
        </div>
    <?php } ?>

    <?php if ($scenario == 'register' || $scenario == 'update' || $scenario == 'recovery') { ?>
        <?php if (CCaptcha::checkRequirements()): ?>
            <br/><br/>
            <div class="row">
                <div class="small-7 large-5 columns form_row">
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
    <?php } ?>

    <?php if ($scenario == 'register') { ?>
        <br/><br/>
        <div class="row">
            <div class="small-7 large-5 columns form_row">
                <?php echo $form->labelEx($model, 'conditions'); ?>
                <?php echo $form->checkBox($model, 'conditions'); ?> Acepto las <?php echo CHtml::link(Yii::t('app', 'Conditions'), array('/site/page', 'view' => 'conditions'), array('target' => '_blank')); ?>.
                <?php echo $form->error($model, 'conditions'); ?> 
            </div>
        </div>
    <?php } ?>
    <br/><br/>
    <div class="row">
        <div class="small-10 large-8 columns form_row buttons small-centered">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Sign up for Demos') : Yii::t('app', 'Save changes'), array('class' => 'button large expand', 'onclick' => '$("#procesingmodal").foundation(\'reveal\', \'open\');')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<div id="procesingmodal" class="reveal-modal">
  <h2><?php echo Yii::t('app','Processing') ?></h2>
  <p><?php echo Yii::t('app','It will take just a moment, wait please') ?>.</p>
</div>

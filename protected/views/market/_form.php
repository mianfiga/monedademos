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
        'id' => 'market-ad-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
            ));
    ?>

    <p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required') ?>.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="small-12 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'title'); ?>
                <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 127)); ?>
                <?php echo $form->error($model, 'title'); ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="small-6 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'class'); ?>
                <div class="list">
                    <?php echo $form->radioButtonList($model, 'class', MarketAd::classOptions()); ?>
                </div>
                <?php echo $form->error($model, 'class'); ?>
            </div>
        </div>
        <div class="small-6 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'type'); ?>
                <div class="list">
                    <?php echo $form->radioButtonList($model, 'type', MarketAd::typeOptions()); ?>
                </div>
                <?php echo $form->error($model, 'type'); ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="form_row">
        <?php echo $form->labelEx($model, 'summary'); ?>
        <?php echo $form->textArea($model, 'summary', array('rows' => 4, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'summary'); ?>
    </div>
    <br/>
    <div class="form_row">
        <div class="row">
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'form_price'); ?>
            </div>
        </div>
        <div class="row collapse">
            <div class="small-10 large-4 columns">
                <?php echo $form->textField($model, 'form_price', array('size' => 10, 'maxlength' => 10)); ?>
            </div>
            <div class="small-2 large-8 columns form_currency_symbol">
                &nbsp;đ
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <?php echo Yii::t('app', 'Examples: "12", "12.03" or "12.30" mind the dot and two digits when using cents.'); ?>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <?php echo $form->error($model, 'form_price'); ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="small-12 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'description'); ?>
                <?php echo $form->textArea($model, 'description', array('rows' => 12, 'cols' => 50)); ?>
                <?php echo $form->error($model, 'description'); ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="small-10 large-6 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'form_image'); ?>
                <?php echo $form->fileField($model, 'form_image', array('maxlength' => 254)); ?>
                <br/> Sube sólo aquellas fotos de las que seas propietario o tengas los derechos para difundir.
                <?php echo $form->error($model, 'form_image'); ?>
            </div>
        </div>
    </div>
    <br/>

    <?php /*
      <div class="form_row">
      <?php echo $form->labelEx($model,'mailmode'); ?>
      <div class="list">
      <?php echo $form->radioButtonList($model,'mailmode',MarketAd::mailmodeOptions()); ?>
      </div>
      <?php echo $form->error($model,'mailmode'); ?>
      </div>

    <div class="row">
        <div class="small-12 columns">

            <div class="form_row">
                <?php echo $form->labelEx($model, 'visible'); ?>
                <?php echo $form->checkBox($model, 'visible'); ?>
                <?php echo $form->error($model, 'visible'); ?>
            </div>
        </div>
    </div>*/ ?>
<br/>
    <div class="row">
        <div class="small-7 large-5 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'expiration'); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'language' => 'es',
                    'model' => $model, // Model object
                    'attribute' => 'expiration', // Attribute name
                    'options' => array(
//									'showOn'=>'button',
//									'showButtonPanel'=>true,
                        'yearRange' => date('Y') . ':' . (date('Y') + 1),
                        'defaultDate' => '+30d',
                        'changeMonth' => true,
                        'changeYear' => true,
                        'dateFormat' => 'yy-mm-dd',
//									'buttonText'=>'',
                    ), // jquery plugin options
                    'htmlOptions' => array('readonly' => true) // HTML options
                ));
                ?>
                <?php echo $form->error($model, 'expiration'); ?>
            </div>
        </div>
    </div>
<br/>
    <div class="row">
        <div class="small-7 large-5 columns">

            <div class="form_row">
                <?php echo $form->labelEx($model, 'zip'); ?>
                <?php echo $form->textField($model, 'zip', array('size' => 16, 'maxlength' => 16)); ?>
                <?php echo $form->error($model, 'zip'); ?>
            </div>
        </div>
    </div>
<br/>
    <div class="row">
        <div class="small-7 large-5 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'anonymous'); ?>
                <?php echo $form->checkBox($model, 'anonymous'); ?>
                <?php echo $form->error($model, 'anonymous'); ?>
            </div>
        </div>
    </div>
<br/>
    <div class="form_row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'button large', 'onclick' => '$("#procesingmodal").foundation(\'reveal\', \'open\');')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<div id="procesingmodal" class="reveal-modal">
  <h2><?php echo Yii::t('app','Processing') ?></h2>
  <p><?php echo Yii::t('app','It will take just a moment, wait please') ?>.</p>
</div>

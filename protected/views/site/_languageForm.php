<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'mobile-form',
        'action' => CHtml::normalizeUrl(array('site/language')),
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>
    <div class="form_row">
        <div class="row">
            <div class="small-12 columns">
                <?php echo $form->hiddenField($model, 'url'); ?>
                <?php echo $form->labelEx($model, 'language'); ?>
            </div>
        </div>
        <div class="row collapse">
            <div class="small-9 columns">
                <?php echo $form->dropDownList($model, 'language', Site::languageList()); ?>
            </div>
            <div class="small-3 columns">
                <?php echo CHtml::submitButton(Yii::t('app','change'), array('class' => 'tiny button secondary')); ?>
            </div>
        </div>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->

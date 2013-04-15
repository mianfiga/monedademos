
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'accountnumberfilter-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="form_row">
        <div class="row">
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'account_number'); ?> 
            </div>
        </div>
        <div class="row collapse">
            <div class="small-9 columns">
                <?php echo $form->dropDownList($model, 'account_number', $accountList, array('class' => 'small')); ?> 
            </div>
            <div class="small-3 columns buttons">
                <?php echo CHtml::submitButton(Yii::t('app', 'change'), array('class' => 'button secondary small')); ?>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <?php echo $form->error($model, 'account_number'); ?>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
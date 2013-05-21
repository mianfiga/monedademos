<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'market-joined-join-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $model->comment ?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="small-12 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'form_comment'); ?>
                <?php echo $form->textArea($model, 'form_comment', array('rows' => 4, 'cols' => 50)); ?>
                <?php echo $form->error($model, 'form_comment'); ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="small-12 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'show_mail'); ?>
                <?php echo $form->checkBox($model, 'show_mail'); ?>
                <?php echo $form->error($model, 'show_mail'); ?>
                <?php echo Yii::t('market', 'Uncheck this field in case you don\'t want to share your e-mail account with the person that created this advertisement'); ?>.
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="small-12 large-6 columns">
            <div class="form_row buttons">
                <?php echo CHtml::submitButton(Yii::t('market', 'Join'), array('class' => 'button large expand')); ?>
            </div>

        </div>
    </div>


    <?php $this->endWidget(); ?>

</div><!-- form -->

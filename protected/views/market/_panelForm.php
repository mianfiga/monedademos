<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'market-joined-join-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $model->comment ?>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'form_comment'); ?>
                <?php echo $form->textArea($model, 'form_comment', array('rows' => 4, 'cols' => 50)); ?>
                <?php echo $form->error($model, 'form_comment'); ?>
            </div>
        </div>
    </div>

    <?php /* 	<div class="row">
      <?php //echo $form->labelEx($model,'email_comment'); ?>
      <?php echo $form->checkBox($model,'email_comment'); ?>
      Check this to send a copy of this comment by e-email.
      <?php echo $form->error($model,'email_commnet'); ?>
      </div> */ ?>
    <br/>
    <div class="row">
        <div class="small-12 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'status'); ?>
                <?php echo $form->dropDownList($model, 'status', MarketJoined::statusList()); ?>
                <?php echo $form->error($model, 'email_commnet'); ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="small-12 large-6 columns">
            <div class="form_row buttons">
                <?php echo CHtml::submitButton(Yii::t('market', 'Update'), array('class' => 'button large expand'))?>
            </div>
            
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->

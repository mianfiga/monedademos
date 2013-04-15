<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Contributions') => array('index'),
    Yii::t('app', 'Contact user'),
);
?>

<h1><?php echo Yii::t('app', 'Contact user') ?></h1>

<?php if (Yii::app()->user->hasFlash('contact')): ?>

    <div class="flash-success">
    <?php echo Yii::app()->user->getFlash('contact'); ?>
    </div>

<?php else: ?>

    <div class="form">

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'contact-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
                ));
        ?>

        <p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required') ?>.</p>

                <?php echo $form->errorSummary($model); ?>
        <fieldset>
            <legend><?php echo Yii::t('app','Sender') ?></legend>
            <div class="form_row">
                <?php echo $form->labelEx($model, 'name'); ?>
                <?php echo $logged->name ?>
            </div>
            <br/>
            <div class="form_row">
                <?php echo $form->labelEx($model, 'email'); ?>
                <?php echo $logged->email ?>
            </div>
        </fieldset>
        <br/>
        <div class="form_row">
    <?php echo $form->labelEx($model, 'subject'); ?>
    <?php echo $form->textField($model, 'subject', array('size' => 60, 'maxlength' => 128)); ?>
            <?php echo $form->error($model, 'subject'); ?>
        </div>
        <br/>
        <div class="form_row">
    <?php echo $form->labelEx($model, 'body'); ?>
        <?php echo $form->textArea($model, 'body', array('rows' => 6, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'body'); ?>
        </div>

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
        <br/>
        <div class="form_row buttons">
        <?php echo CHtml::submitButton(Yii::t('app', 'Send'), array('class' => 'button')); ?>
        </div>

    <?php $this->endWidget(); ?>

    </div><!-- form -->

<?php endif; ?>

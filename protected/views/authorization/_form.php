<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'account-form',
        'enableAjaxValidation' => false,
            ));

    $scenario = $model->getScenario();
    ?>

    <p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required') ?>.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php if ($scenario == 'new') { ?>
        <div class="form_row">
            <?php echo $form->labelEx($model, 'username'); ?>
            <?php echo $form->textField($model, 'username', array('size' => 20, 'maxlength' => 127)); ?>
            <?php echo $form->error($model, 'username'); ?>
        </div>
    <?php } ?>

    <?php if (0) {// cuando el usuario logeado sea holder de la cuenta y la autorización a editar no se refiera a un holder
        ?>
        <div class="form_row">
            <?php echo $form->labelEx($model, 'class'); ?>
            <?php echo $form->textField($model, 'class', array('size' => 6, 'maxlength' => 6)); ?>
            <?php echo $form->error($model, 'class'); ?>
        </div>
    <?php } ?>

    <div class="row">
        <div class="small-12 large-7 columns">
            <div class="form_row">
                <?php echo $form->labelEx($model, 'title'); ?>
                <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 127)); ?>
                <?php echo $form->error($model, 'title'); ?>
            </div>
        </div>
    </div>

    <?php if ($scenario == 'update') { ?>
        <br/>
        <div class="row">
            <div class="small-10 large-6 columns">
                <div class="form_row">
                    <?php echo $form->labelEx($model, 'user_password'); ?>
                    <?php echo $form->passwordField($model, 'user_password', array('size' => 60, 'maxlength' => 128)); ?>
                    <?php echo $form->error($model, 'user_password'); ?>
                </div>
            </div>
        </div>
        <br/>
    <?php } ?>

    <?php if ($scenario == 'update') { ?>
        <div class="row">
            <div class="small-10 large-6 columns">
                <div class="form_row">
                    <?php echo $form->labelEx($model, 'plain_password'); ?>
                    <?php echo $form->passwordField($model, 'plain_password', array('size' => 60, 'maxlength' => 128)); ?>
                    <?php echo $form->error($model, 'plain_password'); ?>
                </div>
            </div>
        </div>

    <?php } ?>

    <?php if ($scenario == 'update') { ?>
        <div class="row">
            <div class="small-10 large-6 columns">
                <div class="form_row">
                    <?php echo $form->labelEx($model, 'password2'); ?>
                    <?php echo $form->passwordField($model, 'password2', array('size' => 60, 'maxlength' => 128)); ?>
                    <?php echo $form->error($model, 'password2'); ?>
                </div>
            </div>
        </div>

    <?php } ?>
    <br/>
    <div class="form_row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save changes'), array('class' => 'button')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

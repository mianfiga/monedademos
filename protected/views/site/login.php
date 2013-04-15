<?php
$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
    'Sign in',
);
?>
<div class="row">
    <div class="small-12 large-4 columns">
        <div class="row">
            <div class="small-12 columns"><br/>
                <div class="panel">
                <h1><?php echo Yii::t('app', 'Sign in') ?></h1>
                <div class="row">
                    <div class="small-12 columns">
                        <div class="form">
                            <?php
                            $form = $this->beginWidget('CActiveForm', array(
                                'id' => 'login-form',
                                'enableClientValidation' => true,
                                'clientOptions' => array(
                                    'validateOnSubmit' => true,
                                ),
                                    ));
                            ?>

                            <?php /* 	<p class="note">Fields with <span class="required">*</span> are required.</p> */ ?>

                            <div class="form_row">
                                <?php echo $form->labelEx($model, 'username'); ?>
                                <?php echo $form->textField($model, 'username'); ?>
                                <?php echo $form->error($model, 'username'); ?>
                            </div>

                            <div class="form_row">
                                <?php echo $form->labelEx($model, 'password'); ?>
                                <?php echo $form->passwordField($model, 'password'); ?>
                                <?php echo $form->error($model, 'password'); ?>
                            </div>

                            <div class="form_row rememberMe">
                                <?php echo $form->checkBox($model, 'rememberMe'); ?>
                                <?php echo $form->label($model, 'rememberMe'); ?>
                                <?php echo $form->error($model, 'rememberMe'); ?>
                            </div>
                            <br/><br/>
                            <div class="form_row buttons">
                                <?php echo CHtml::submitButton(Yii::t('app', 'Sign in'), array('class' => 'large button expand secondary')); ?>
                            </div>

                            <?php echo Yii::t('app', 'Have you forgotten your username/password? contact us to resolve it at contacto@monedademos.es'); ?>
                            <?php $this->endWidget(); ?>
                        </div><!-- form -->
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="small-12 large-8 columns hide-for-small">
        <h2><?php echo Yii::t('app','New to Demos?') ?> <span class="subheader"><?php echo Yii::t('app','Sign up') ?></span></h2>
        <?php echo $this->renderPartial('/user/_form', array('model' => $modelRegister)); ?>
    </div>
    <div class="small-12 large-8 columns show-for-small">
        <h2><?php echo Yii::t('app','New to Demos?') ?> <span class="subheader"><?php echo Yii::t('app','Sign up') ?></span></h2>
        <?php echo CHtml::link(Yii::t('app', 'Join now'), array('/user/create'), array('class' => 'site_join_button large button expand')); ?>
    </div>
</div>

<?php /* <p>Please fill out the following form with your login credentials:</p> */ ?>

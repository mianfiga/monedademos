<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'transaction-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>
    <div class="form_row">
        <div class="row">
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'form_amount'); ?>
            </div>
        </div>
        <div class="row collapse">
            <div class="small-10 large-4 columns">
               <?php echo $form->numberField($model, 'form_amount', array('size' => 10, 'maxlength' => 10, 'autofocus' => 'autofocus', 'step' => '0.01')); ?>
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
                <?php echo $form->error($model, 'form_amount'); ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="form_row">
        <div class="row">
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'charge_account_number'); ?>
            </div>
        </div>
        <div class="row">
            <div class="small-10 large-4 columns">
                <?php
                if (count($charge_accounts) > 0)
                    echo $form->dropDownList($model, 'charge_account_number', $charge_accounts, array('options' => array(Yii::app()->session['accountNumber'] => array('selected' => true))));
                else
                    echo $form->textField($model, 'charge_account_number', array('size' => 23, 'maxlength' => 23, 'placeholder' => Yii::t('app','Ask your customer for his/her account number')));
                ?>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <?php echo $form->error($model, 'charge_account_number'); ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="form_row">
        <div class="row">
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'deposit_account_number'); ?>
            </div>
        </div>
        <div class="row">
            <div class="small-10 large-4 columns">
                <?php
                if (count($deposit_accounts) > 0)
                    echo $form->dropDownList($model, 'deposit_account_number', $deposit_accounts, array('options' => array(Yii::app()->session['accountNumber'] => array('selected' => true))));
                else
                    echo $form->textField($model, 'deposit_account_number', array('size' => 23, 'maxlength' => 23, 'placeholder' => Yii::t('app','Ask your seller for his/her account number')));
                ?>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <?php echo $form->error($model, 'deposit_account_number'); ?>
            </div>
        </div>
    </div>
    <br/>
    <div class="form_row">
        <div class="row">
            <div class="small-12 columns">
                <?php echo $form->labelEx($model, 'subject'); ?>
            </div>
        </div>
        <div class="row">
            <div class="small-12 large-10 columns">
                <?php echo $form->textField($model, 'subject', array('size' => 60, 'maxlength' => 255)); ?>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <?php echo $form->error($model, 'subject'); ?>
            </div>
        </div>
    </div>

    <div class="form_row">
    </div>

    <div class="form_row buttons">
        <?php echo $form->hiddenField($model, 'sid'); ?>
        <?php echo CHtml::submitButton(Yii::t('app', 'Continue'), array('class' => 'button large expand')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

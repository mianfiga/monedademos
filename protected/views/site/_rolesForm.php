<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'role-form',
        'action' => CHtml::normalizeUrl(array('site/roles')),
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
                <?php echo $form->labelEx($model, 'role'); ?>
            </div>
        </div>
        <div class="row collapse">
            <div class="small-9 columns">
                <?php echo $form->dropDownList($model, 'role', Yii::app()->user->roles,array('class' => 'small')); ?>
            </div>
            <div class="small-3 columns">
                <?php echo CHtml::submitButton(Yii::t('app','change'), array('class' => 'small button secondary')); ?>
            </div>
        </div>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->

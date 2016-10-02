<?php
/* @var $this RateController */
/* @var $model Rate */
if ($model != null) {
    ?>

    <h3 class="no-space"><?php echo Yii::t('app', 'Rate {name} in this {object}', array('{name}' => $model->to->name, '{object}' => Sid::getName($model->sid))) ?></h3>
    <div class='mk_updated'><?php echo Yii::t('app', 'Updated') . ': ' . $model->updated ?></div>
    <?php echo $this->renderPartial('/rate/_form', array('model' => $model)); ?>

<?php }
?>

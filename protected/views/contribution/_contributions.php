<?php
/* @var $this contributionController */
/* @var $model Transaction */
?>

<div class="row">
    <div class="small-12 large-4 columns">
        <h3><?php echo Yii::t('app', 'Client contribution') ?></h3>
    </div>
    <div class="small-12 large-8 columns">

        <?php
        $this->widget('CStarRating', array(
            'name' => 'chargeRating',
            'model' => $model->chargeEntity,
            'attribute' => 'rate',
            'minRating' => 1,
            'maxRating' => 5,
            'starCount' => 5,
            'readOnly' => true,
            'starWidth' => '64',
        ));
        echo '&nbsp;&nbsp;' . Yii::t('app', 'In {count} rates', array('{count}' => $model->chargeEntity->rates));
        ?>
    </div>
</div>
<?php
echo $this->renderPartial('//contribution/_view', array('data' => $model->chargeEntity->getObject()));
?>

<div class="row">
    <div class="small-12 large-4 columns">

        <h3><?php echo Yii::t('app', 'Vendor contribution') ?></h3>
    </div>
    <div class="small-12 large-8 columns">
        <?php
        $this->widget('CStarRating', array(
            'name' => 'depositRating',
            'model' => $model->depositEntity,
            'attribute' => 'rate',
            'minRating' => 1,
            'maxRating' => 5,
            'starCount' => 5,
            'readOnly' => true,
            'starWidth' => '64',
        ));
        echo '&nbsp;&nbsp;' . Yii::t('app', 'In {count} rates', array('{count}' => $model->depositEntity->rates));
        ?>
    </div>
</div>
<?php
echo $this->renderPartial('//contribution/_view', array('data' => $model->depositEntity->getObject()));
?>

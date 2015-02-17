<?php
/* @var $this RateController */
/* @var $dataProvider CActiveDataProvider */
/* @var $entity Entity */
?>
<br/>
<div class="row">
    <div class="small-12 <?php echo (isset($entity) ? 'large-6' : '') ?> columns"><h3 class="subheader"><?php echo Yii::t('app', 'Rates & Comments') ?></h3></div>
    <?php if (isset($entity)) { ?>
        <div class="small-12 large-6 columns">
            <?php
            echo $this->renderPartial('/rate/_average', array(
                'entity' => $entity,
            ));
            ?>
        </div>
    <?php } ?>

</div>
<br/>


<?php
$this->widget('zii.widgets.CListView', array(
    'id' => 'rates-grid',
    'dataProvider' => $dataProvider,
    'itemView' => '/rate/_view',
    'sortableAttributes' => array(
        'updated',
        'added',
    ),
));
?>
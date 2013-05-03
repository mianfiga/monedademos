<?php
/* @var $this RateController */
/* @var $model Rate */
/* @var $entity Entity */
?>
<br/>
<div class="row">
    <div class="small-12 large-6 columns"><h3><?php echo Yii::t('app', 'Rates & Comments') ?></h3></div>
    <div class="small-12 large-6 columns">
        <?php
        if (isset($entity)) {
            $this->widget('CStarRating', array(
                'name' => 'rating',
                'model' => $entity,
                'attribute' => 'rate',
                'minRating' => 1,
                'maxRating' => 5,
                'starCount' => 5,
                'readOnly' => true,
                'starWidth' => '64',
            ));
        }
        echo '&nbsp;&nbsp;' . Yii::t('app', 'Average rate');
        ?>
    </div>

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
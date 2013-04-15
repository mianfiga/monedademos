<?php
if (!isset($dataProvider))
    $dataProvider = null;
if (!isset($highlight))
    $highlight = null;
?>

<div class="row">
    <div class="small-12 columns">
        <?php echo CHtml::link(Yii::t('app', 'Back to Account'), array('transaction/index'), array('class' => 'site_join_button button secondary')); ?>
    </div>
    <?php /*    <div class="small-6 large-3 columns">
      <?php echo CHtml::link(Yii::t('app', 'Charge'), array('charge'), array('class' => 'site_join_button expand button'. ($highlight!='charge'?' secondary':''))); ?>
      </div>
      <div class="small-6 large-3 columns">
      <?php echo CHtml::link(Yii::t('app', ($dataProvider == null ? 'View movements' : 'Hide')), array(($dataProvider == null ? 'list' : 'index')), array('class' => 'site_join_button expand secondary button')); ?>
      </div>
      <div class="small-6 large-3 columns">
      <?php echo CHtml::link(Yii::t('app', 'View pending'), array('pending/index'), array('class' => 'site_join_button expand secondary button')); ?>
      </div>

     */ ?>
</div>
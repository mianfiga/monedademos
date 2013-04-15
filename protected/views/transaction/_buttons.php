<?php
if (!isset($highlight)) $highlight = null;

?>

<div class="row">
    <div class="small-6 large-3 columns">
        <?php echo CHtml::link(Yii::t('app', 'Pay'), array('transfer'),
                array('class' => 'site_join_button large expand button'. ($highlight=='charge'?' secondary':''))); ?>
    </div>
    <div class="small-6 large-3 columns">
        <?php echo CHtml::link(Yii::t('app', 'Charge'), array('charge'), array('class' => 'site_join_button large expand button'. ($highlight!='charge'?' secondary':''))); ?>
    </div>
    <div class="small-6 large-3 columns">
        <?php echo CHtml::link(Yii::t('app', ($showingTransactions ?'Hide':'View movements')), array(($showingTransactions?'index':'list')), array('class' => 'site_join_button large expand secondary button')); ?>
    </div>

    <div class="small-6 large-3 columns">
        <?php echo CHtml::link(Yii::t('app', 'View pending'), array('pending/index'), array('class' => 'site_join_button large expand secondary button')); ?>
    </div>
</div>

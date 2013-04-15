<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Account') => array('index'),
    Yii::t('app', 'New Transaction'),
);


$this->menu = array(
    array('label' => Yii::t('app', 'Select Account'), 'url' => array('index')),
    array('label' => Yii::t('app', 'New Transaction'), 'url' => array('transfer')),
    array('label' => Yii::t('app', 'New Charge'), 'url' => array('charge')),
    array('label' => Yii::t('app', 'New Movement'), 'url' => array('movement')),
    array('label' => Yii::t('app', 'List Transactions'), 'url' => array('list')),
    array('label' => Yii::t('app', 'List Pending'), 'url' => array('Pending/index')),
        /* 	array('label'=>'Manage Transaction', 'url'=>array('admin')), */
);
?>
<div class="row">
    <div class="small-12 large-4 push-8 columns">
        <br/><?php echo $this->renderPartial('_navigation', array('highlight' => $action)); ?>
    </div>

    <div class="small-12 large-8 pull-4 columns">
        <h1><?php echo Yii::t('app', 'New Transaction'); ?> (<?php echo Transaction::actionsToTxt($action) ?>)</h1>
    </div>
</div>
<div class="row">
    <div class="small-12 columns steps">
        <h3><?php echo Yii::t('app', '1. Fill data') ?> - <span class="subheader"><?php echo Yii::t('app', '2. Confirm operation') ?></span></h3>
    </div>
</div>

<div class="row">
    <div class="small-12 columns">
        <?php
        echo $this->renderPartial('_fillForm', array('model' => $model,
            'charge_accounts' => $charge_accounts,
            'deposit_accounts' => $deposit_accounts));
        ?>
    </div>
</div>


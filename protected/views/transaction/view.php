<?php
$this->breadcrumbs = array(
    'Transactions' => array('index'),
    ($model != null ? $model->id : null),
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
<?php
if ($model == null) {
    ?>
    <h1><?php echo (($charge_errors | $deposit_errors) != 0 ? Yii::t('app', 'Transaction NOT completed') : Yii::t('app', 'Transaction not accessible')) ?></h1>
    <?php
    if ($charge_errors != 0)
        echo Yii::t('app', 'Errors found in the charge (source) account') . ".<br/>";
    if ($deposit_errors != 0)
        echo Yii::t('app', 'Errors found in the deposit (destination) account') . ".<br/>";
}
else {
    ?>
    <h1>View Transaction #<?php echo $model->id; ?></h1>

    <?php
    $chargeHolders = $model->chargeAccount->holders;
    $depositHolders = $model->depositAccount->holders;
    $isChargeHolder = false;
    $isDepositHolder = false;

    foreach ($chargeHolders as $holder) {
        $chargeHolder = $holder;
        if ($holder->id == $model->charge_entity) {
            $isChargeHolder = true;
            break;
        }
    }

    foreach ($depositHolders as $holder) {
        $depositHolder = $holder;
        if ($holder->id == $model->deposit_entity) {
            $isDepositHolder = true;
            break;
        }
    }

    $this->widget('zii.widgets.CDetailView', array(
        'data' => $model,
        'attributes' => array(
            'id',
            array(
                'label' => Yii::t('app', 'Date'),
                'type' => 'raw',
                'value' => date('d-m-Y H:m:i', Transaction::convertDatetime($model->executed_at))
            ),
            'class',
            array(
                'label' => Yii::t('app', 'Amount'),
                'type' => 'raw',
                'value' => CHtml::encode($model->getAmount())
            ),
            array(
                'label' => Yii::t('app', 'Charge Account (Source)'),
                'type' => 'raw',
                'value' => CHtml::encode($model->getChargeAccountNumber())
                . '; ' . $model->chargeEntity->name . ' ' . $model->chargeEntity->surname
                . (!$isChargeHolder ? ' (' . $chargeHolder->name . ' ' . $chargeHolder->surname . ')' : ''),
            ),
            array(
                'label' => Yii::t('app', 'Deposit Account (Destination)'),
                'type' => 'raw',
                'value' => CHtml::encode($model->getDepositAccountNumber())
                . '; ' . $model->depositEntity->name . ' ' . $model->depositEntity->surname
                . (!$isDepositHolder ? ' (' . $depositHolder->name . ' ' . $depositHolder->surname . ')' : ''),
            ),
            'subject',
        ),
    ));
}
?>
<br/>
<div class="row">
    <div class="small-6 large-3 columns">
        <?php echo CHtml::link(Yii::t('app', 'View movements'), array('transaction/list'), array('class' => 'button expand'));
        ?> 
    </div>
    <div class="small-6 large-3 columns">
        <?php echo CHtml::link(Yii::t('app', 'Back to Account'), array('transaction/index'), array('class' => 'button expand secondary'));
        ?>
    </div>

    <div class="small-6 large-3 columns">
        <?php echo CHtml::link(Yii::t('app', 'Pay'), array('transfer'), array('class' => 'site_join_button expand button'));
        ?>
    </div>
    <div class="small-6 large-3 columns">
        <?php echo CHtml::link(Yii::t('app', 'Charge'), array('charge'), array('class' => 'site_join_button expand button secondary'));
        ?>
    </div>
</div>
<br/>
<?php
if (isset($rate)) {
    //$this->renderPartial('/rate/_embedded', array('model' => $rate));
}
?>
<?php
if (isset($model) && $model !== null) {
    $this->renderPartial('/contribution/_contributions', array('model' => $model));
}
?>




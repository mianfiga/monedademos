<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Account'),
);

/* $this->menu = array(
  array('label' => Yii::t('app', 'Select Account'), 'url' => array('index')),
  array('label' => Yii::t('app', 'New Transaction'), 'url' => array('transfer')),
  array('label' => Yii::t('app', 'New Charge'), 'url' => array('charge')),
  array('label' => Yii::t('app', 'New Movement'), 'url' => array('movement')),
  array('label' => Yii::t('app', 'List Transactions'), 'url' => array('list')),
  array('label' => Yii::t('app', 'List Pending'), 'url' => array('Pending/index')),
  // 	array('label'=>'Manage Transaction', 'url'=>array('admin')),
  ); */

//tooltip javascript
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/vendor/custom.modernizr.js');
$cs->registerScriptFile($baseUrl . '/js/foundation.min.js');
$cs->registerScriptFile($baseUrl . '/js/vendor/custom.modernizr.js');
$cs->registerScript('foundation_tooltip', '$(document).foundation(\'tooltips\');', CClientScript::POS_READY);
?>
<div class="row">
    <div class="small-12 large-6 columns">
        <h1 class="no-space"><?php echo Yii::t('app', 'Account') ?>: <span class="subheader has-tip" data-tooltip title="<?php echo Yii::t('app', 'It\'s important that you copy this number and take it with you, you\'ll need it to pay') ?>"><?php echo $accountNumber ?></span></h1>
        <?php echo $auth->title; ?>
    </div>
    <div class="small-12 large-2 columns">
        <br/><?php echo CHtml::link(Yii::t('app', 'Edit account'), array('authorization/update', 'id' => $accountNumber), array('class' => 'button secondary')); ?>
    </div>

    <div class="small-12 large-4 columns">
        <br/>
        <?php
        echo $this->renderPartial('_accountNumberFilterForm', array('model' => $form_model,
            'accountList' => $accountList,
            'accountNumber' => $accountNumber,
        ));
        ?>
    </div>

</div>
<div class="row">
    <div class="small-12 large-5 columns">
        <h3><?php echo Yii::t('app', 'Credit') ?>: <?php echo Transaction::amountSystemToUser($account->credit) ?></h3>
    </div>
    <div class="small-12 large-7 columns">
        <h3><?php echo Yii::t('app', 'This month balance') ?>: <?php echo Transaction::amountSystemToUser($account->earned - $account->spended) ?></h3>
    </div>    
</div>

<?php
echo $this->renderPartial('_buttons', array('showingTransactions' => isset($model)));
?>


<?php //echo Yii::t('app', 'You should copy your Account Number and save it to your mobile or wallet, you will need it in th future to pay and charge to other users'); ?>

<?php
/* if ($dataProvider !== null)
  $this->widget('zii.widgets.CListView', array(
  'dataProvider' => $dataProvider,
  'itemView' => '_view',
  )); */
?>
<?php
if (isset($model)) {
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'transaction-grid',
        'dataProvider' => $model->search(),
//        'filter' => $model,
        'columns' => array(
            array(
                'class' => 'CButtonColumn',
                'template' => '{view}',
                'buttons' => array(
                    'view' => array(
                        'url' => 'Yii::app()->createUrl("transaction/view", array("id"=>$data->id))', //A PHP expression for generating the URL of the button.
                    ),
                ),
            ),
            'executed_at',
            'class',
            'foreign_account_number',
            'subject',
            array(
                'name' => 'amount',
                'value' => '($data->is_payment?\'- \':\'\').$data->getAmount()',
                'htmlOptions' => array('class' => 'amounts'),
            )
        ),
    ));
} else { ?>
    <hr />
<?php
    echo $this->renderPartial('_authorization', array('account' => $account,
        'accountNumber' => $accountNumber));
}
?>



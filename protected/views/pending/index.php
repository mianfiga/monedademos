<?php
$this->breadcrumbs = array(
    Yii::t('app', 'Account') => array('Transaction/index'),
    Yii::t('app', 'Pending transactions'),
);

/* $this->menu=array(
  array('label'=> Yii::t('app','Select Account'), 'url'=>array('Transaction/index')),
  array('label'=> Yii::t('app','New Transaction'), 'url'=>array('Transaction/transfer')),
  array('label'=> Yii::t('app','New Charge'), 'url'=>array('Transaction/charge')),
  array('label'=> Yii::t('app','New Movement'), 'url'=>array('Transaction/movement')),
  array('label'=> Yii::t('app','List Transactions'), 'url'=>array('Transaction/list')),
  array('label'=> Yii::t('app','List Pending'), 'url'=>array('Pending/index')),
  //	array('label'=>'Manage Transaction', 'url'=>array('admin')),

  ); */
?>
<div class="row">
    <div class="small-12 large-4 push-8 columns">
        <br/><?php echo $this->renderPartial('/transaction/_navigation'); ?>
    </div>

    <div class="small-12 large-8 pull-4 columns">
        <h1><?php echo Yii::t('app', 'Pending transactions') ?>: <span class="subheader"><?php echo $accountNumber ?></span></h1>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $dataProvider,
            'itemView' => '_view',
        ));
        ?>
    </div>
</div>
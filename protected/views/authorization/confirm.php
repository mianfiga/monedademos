<?php
/* $this->breadcrumbs=array(
  'Transaction'=>array('index'),
  'Confirm',
  ); */

/* $this->menu=array(
  array('label'=>'List Transaction', 'url'=>array('index')),
  array('label'=>'Manage Transaction', 'url'=>array('admin')),
  ); */
?>
<div class="row">
    <div class="small-12 large-4 push-8 columns">
        <br/><?php echo $this->renderPartial('/transaction/_navigation'); ?>
    </div>

    <div class="small-12 large-8 pull-4 columns">
        <h1><?php echo Yii::t('app', 'New Transaction'); ?> (<?php echo Transaction::actionsToTxt(Yii::app()->session['operations'][$model->sid]['action']) ?>)</h1>
    </div>
</div>
<div class="row">
    <div class="small-12 columns steps">
        <h3><span class="subheader"><?php echo Yii::t('app', '1. Fill data') ?></span> - <?php echo Yii::t('app', '2. Confirm operation') ?></h3>
    </div>
</div>
<?php
$opmodel = Yii::app()->session['operations'][$model->sid]['model'];
?>

<?php echo $this->renderPartial('_confirmForm', array('model' => $model, 'model2' => $model2)); ?>
<br/>
<h3><?php echo Yii::t('app', 'Client contribution') ?></h3>
<?php
$client = Authorization::splitAccountNumber($opmodel->getChargeAccountNumber());
echo $this->renderPartial('//contribution/_view', array('data' => User::model()->findByPk($client['user_id'])));
?>

<h3><?php echo Yii::t('app', 'Vendor contribution') ?></h3>
<?php
$vendor = Authorization::splitAccountNumber($opmodel->getDepositAccountNumber());
echo $this->renderPartial('//contribution/_view', array('data' => User::model()->findByPk($vendor['user_id'])));
?>


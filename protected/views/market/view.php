<?php $this->pageTitle = Site::TITLE . "Market | " . $model->title; ?>
<?php
$this->breadcrumbs = array(
    'Market' => array('index'),
    $model->title,
);

$this->menu = array(
    array('label' => Yii::t('market', 'Join this Ad'), 'url' => array('join', 'id' => $model->id)),
    array('label' => Yii::t('market', 'List Advertisements'), 'url' => array('index')),
    array('label' => Yii::t('market', 'Manage Advertisement'), 'url' => array('panel', 'id' => $model->id), 'visible' => $model->created_by == Yii::app()->user->getId()),
    array('label' => Yii::t('market', 'Update Advertisement'), 'url' => array('update', 'id' => $model->id), 'visible' => $model->created_by == Yii::app()->user->getId()),
);
?>
<div class="mk_updated"><?php echo Yii::t('market', 'Updated') . ':&nbsp;' . date('d/m/Y', strtotime($model->updated)); ?></div>
<?php echo Yii::t('market', ($model->type == 'offer' ? 'Offering' : 'Looking&nbsp;for') . '&nbsp;' . $model->class) ?><br/>
<div class="mk_join"><?php echo CHtml::link(Yii::t('market', 'Join'), array('join', 'id' => $model->id)); ?></div><h3><?php echo $model->title; ?></h3>

<?php if ($model->image != '') {
    ?>
    <img class="mk_view_img" src="<?php echo Yii::app()->request->baseUrl . '/images/market/' . $model->image ?>" alt="<?php echo CHtml::encode($model->title) ?>"/>
<?php } ?>
<h4 class="subheader"><?php echo Yii::t('market', 'Summary') ?></h4>
<p><?php echo $model->summary; ?></p>
<?php if ($model->description != '') { ?>
    <h4 class="subheader"><?php echo Yii::t('market', 'Description') ?></h4>
    <div><?php echo nl2br($model->description); ?></div>
<?php } ?>
<br/>
<div class="mk_bottom_bar">
    <div class="mk_data mk_price"><?php echo Transaction::amountSystemToUser($model->price) ?></div>
    <?php
    if (Yii::app()->user->getId() && isset($model->joined[0])) {
        $status_list = MarketJoined::statusList();
        ?>
        <div class="mk_data mk_status mk_<?php echo $model->joined[0]->status ?>">Your status: <?php echo $status_list[$model->joined[0]->status] ?></div>
    <?php } ?>
    <?php if ($model->zip != '') { ?>    
        <div class="mk_data mk_zip"><?php echo $model->zip ?></div>
    <?php } ?>
</div>


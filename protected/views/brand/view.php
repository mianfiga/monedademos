<?php $this->pageTitle = Site::TITLE . Yii::t('brand','Contributors') .' | '. $model->name; ?>
<?php
/* @var $this BrandController */
/* @var $model Brand */

$this->breadcrumbs = array(
    Yii::t('brand','Contributors') => array('index'),
    $model->name,
);

/* $this->menu = array(
  array('label' => 'List Brand', 'url' => array('index')),
  array('label' => 'Create Brand', 'url' => array('create')),
  array('label' => 'Update Brand', 'url' => array('update', 'id' => $model->id)),
  array('label' => 'Delete Brand', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
  array('label' => 'Manage Brand', 'url' => array('admin')),
  ); */
?>
<div class="row">
    <div class="small-12 large-7 columns">
        <h1 class="no-space"><?php echo $model->name; ?></h1>
        <?php
        echo $this->renderPartial('/rate/_average', array(
            'entity' => $entity,
        ));
        ?>
        <h4 class="subheader"><?php echo Yii::t('market', 'Summary') ?></h4>
        <p><?php echo $model->summary; ?></p>
        <?php if ($model->description != '') {
            ?>
            <h4 class="subheader"><?php echo Yii::t('market', 'Description') ?></h4>
            <div><?php echo nl2br($model->description); ?></div>
        <?php } ?>
        <br/>
        <hr/>
        <div class="row">
            <div class="small-12 large-9 columns">
                <h3 class="subheader"><?php echo Yii::t('app', 'Promos & Ads'); ?></h3>
            </div>
            <div class="small-12 large-3 columns">
                <?php if ($entity->id == Yii::app()->user->getId()) {
                    echo '&nbsp;' . CHtml::link(Yii::t('market', 'Create Advertisement'), array('market/create'), array('class' => "button small secondary"));
                } ?>
            </div>
        </div>
        <?php
        echo $this->renderPartial('/market/_list', array(
            'dataProvider' => $adsDataProvider,
        ));
        ?>
    </div>
    <div class="small-12 large-5 columns">
<?php if (isset(Yii::app()->user->logged) && $model->created_by == Yii::app()->user->logged) { ?>
            <br/>
            <div class="row">
                <div class="small-12 columns">
            <?php echo '&nbsp;' . CHtml::link(Yii::t('app', 'Edit'), array('update', 'id' => $model->id), array('class' => "button")); ?>
                </div>
            </div>
        <?php } ?>


        <?php if ($model->image != '') { ?>
            <img src="<?php echo Yii::app()->request->baseUrl . '/images/brands/' . $model->image ?>" alt="<?php echo CHtml::encode($model->name) ?>"/>
        <?php } ?>
        <?php
        echo $this->renderPartial('/rate/_list', array(
            'dataProvider' => $ratesDataProvider));
        ?>
    </div>
</div>
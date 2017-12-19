<?php $this->pageTitle = Site::TITLE . Yii::t('brand', 'Contributors') . ' | ' . $model->name; ?>
<?php
/* @var $this BrandController */
/* @var $model Brand */

$this->breadcrumbs = array(
    'Users' => array('index'),
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
      <?php if ($is_admin) { ?>
          <br/>
          <div class="row">
              <div class="small-12 columns">
                <?php echo CHtml::link(Yii::t('app', 'Ver ficha'), array('me'), array('class' => "button secondary")); ?>
                <?php echo '&nbsp;' . CHtml::link(Yii::t('app', 'Edit contribution'), array('edit', 'id' => $entity->id), array('class' => "button secondary")); ?>
                <?php echo '&nbsp;' . CHtml::link(Yii::t('app', 'Update user info'), array('update', 'id' => $entity->id), array('class' => "button")); ?>
              </div>
          </div>
<?php } ?>
        <h1 class="no-space"><?php echo $model->name; ?></h1>
        <?php
        echo $this->renderPartial('/rate/_average', array(
            'entity' => $entity,
        ));
        ?>
        <h4 class="subheader"><?php echo Yii::t('market', 'Summary') ?></h4>
        <p><?php echo strip_tags($model->contribution_title, '<a><img><table>'); ?></p>
        <?php if ($model->contribution_text != '') {
            ?>
            <h4 class="subheader"><?php echo Yii::t('market', 'Description') ?></h4>
            <div><?php echo nl2br(strip_tags($model->contribution_text, '<a><img><table>')); ?></div>
        <?php } ?>
        <br/>
        <hr/>
        <div class="row">
            <div class="small-12 large-9 columns">
                <h3 class="subheader"><?php echo Yii::t('app', 'Promos & Ads'); ?></h3>
            </div>
            <div class="small-12 large-3 columns">
                <?php
                if ($is_admin) {
                    echo '&nbsp;' . CHtml::link(Yii::t('market', 'Create Advertisement'), array(
                        'market/create', 'id' => Entity::get($model)->id), array('class' => "button small secondary")
                    );
                }
                ?>
            </div>
        </div>
<?php
echo $this->renderPartial('/market/_list', array(
    'dataProvider' => $adsDataProvider,
));
?>
    </div>
    <div class="small-12 large-5 columns">
      <?php if ($is_admin) { ?>
          <br/>
          <div class="row">
              <div class="small-12 columns">
                <?php echo CHtml::link(Yii::t('app', 'Connect to Telegram'), 'https://telegram.me/monedademos_bot?start='. $entity->id . '-' . $entity->getMagic(), array('class' => "button")); ?>
                <?php
                if (strpos($model->abilities, User::ABILITY_INVITE) !== false) {
                    echo '&nbsp;' . CHtml::link(Yii::t('app', 'Invite friend'), array('invitation/index'), array('class' => "button secondary"));
                } ?>
              </div>
          </div>
<?php } else {?>
          <br/>
          <div class="row">
              <div class="small-12 columns">
                <?php echo CHtml::link(Yii::t('app', 'Send e-mail'), array('/contribution/contact', 'id' => $model->id), array('class' => "button")); ?>
              </div>
          </div>
      <?php } ?>
          <img src="<?php echo Yii::app()->request->baseUrl . ($model->image != ''?'/images/users/' . $model->image : '/images/nophoto.png') ?>" alt="<?php echo CHtml::encode($model->name) ?>"/>
        <?php
/*        echo $this->renderPartial('/link/_list', array(
            'links' => $entity->links,
            'edit' => $is_admin));*/
        ?>
        <?php
        echo $this->renderPartial('/rate/_list', array(
            'dataProvider' => $ratesDataProvider));
        ?>
    </div>
</div>

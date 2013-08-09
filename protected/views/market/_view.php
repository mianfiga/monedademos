<div class="view mk_<?php echo $data->type ?> <?php echo ($data->expired ? 'mk_expired' : '') ?>">
    <div class="mk_top_bar">
        <div class="mk_type">
            <?php echo Yii::t('market', ($data->type == 'offer' ? 'Offering' : 'Looking&nbsp;for') . '&nbsp;' . $data->class) ?>&nbsp;<?php echo ($data->expired ? Yii::t('market', 'EXPIRED') : '') ?>
        </div>
        <div class="mk_updated"><?php echo Yii::t('market', 'Updated') . ':&nbsp;' . date('d/m/Y', strtotime($data->updated)); ?></div>
    </div>
    <div class="mk_post_top_bar"></div>
    <?php if ($data->image != '') {
        ?>
        <img class="mk_img" src="<?php echo Yii::app()->request->baseUrl . '/images/market/' . MarketAd::THUMB_PREFIX . $data->image ?>" alt="<?php echo CHtml::encode($data->title) ?>"/>
    <?php } ?>
    <?php echo CHtml::link('<img class="mk_button_join" src="' . Yii::app()->request->baseUrl . '/images/join.png" alt="Join" title="Join"/>', array('market/join', 'id' => $data->id)); ?>
    <h4 class="no-space"><?php echo CHtml::link(CHtml::encode($data->title), array('market/view', 'id' => $data->id)); ?></h4>
    <?php echo CHtml::encode($data->summary); ?>
    <?php echo CHtml::link(Yii::t('market', 'view more'), array('market/view', 'id' => $data->id)); ?>

    <div class="mk_bottom_bar">
        <div class="mk_data mk_price"><?php echo Transaction::amountSystemToUser($data->price) ?></div>
        <?php
        if (Yii::app()->user->getId() && isset($data->joined[0])) {
            $status_list = MarketJoined::statusList();
            ?>
            <div class="mk_data mk_status mk_<?php echo $data->joined[0]->status ?>"><?php echo $status_list[$data->joined[0]->status] ?></div>
        <?php } ?>
        <?php if ($data->zip != '') { ?>    
            <div class="mk_data mk_zip"><?php echo $data->zip ?></div>
        <?php } ?>
        <?php
        if (isset(Yii::app()->user->roles) && isset(Yii::app()->user->roles[$data->created_by])){
            echo '&nbsp;'. CHtml::link(Yii::t('app', 'Edit'), array('market/update', 'id' => $data->id), array('class' => "small button"));
            echo '&nbsp;'. CHtml::link(Yii::t('app', 'Manage'), array('market/panel', 'id' => $data->id), array('class' => "small button secondary"));
        }
        ?>
    </div>
    <div class="mk_posbottom"> </div>
    <?php /*

      <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
      <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
      <?php echo CHtml::encode($data->title); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('class')); ?>:</b>
      <?php echo CHtml::encode($data->class); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
      <?php echo CHtml::encode($data->type); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('summary')); ?>:</b>
      <?php echo CHtml::encode($data->summary); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
      <?php echo CHtml::encode($data->price); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
      <?php echo CHtml::encode($data->description); ?>
      <br />

      <?php /*
      <b><?php echo CHtml::encode($data->getAttributeLabel('image')); ?>:</b>
      <?php echo CHtml::encode($data->image); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('mailmode')); ?>:</b>
      <?php echo CHtml::encode($data->mailmode); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('visible')); ?>:</b>
      <?php echo CHtml::encode($data->visible); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('expiration')); ?>:</b>
      <?php echo CHtml::encode($data->expiration); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('created_by')); ?>:</b>
      <?php echo CHtml::encode($data->created_by); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('added')); ?>:</b>
      <?php echo CHtml::encode($data->added); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
      <?php echo CHtml::encode($data->updated); ?>
      <br />

     */ ?>

</div>

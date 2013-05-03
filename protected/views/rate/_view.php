<?php
/* @var $this RateController */
/* @var $data Rate */
?>

<div class="view">
    <div class="mk_updated"><?php echo Yii::t('app','Updated').': '. $data->updated?></div>
    <?php /*
      <b><?php echo CHtml::encode($data->getAttributeLabel('to_id')); ?>:</b>
      <?php echo CHtml::encode($data->to_id); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('from_id')); ?>:</b>
      <?php echo CHtml::encode($data->from_id); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('sid')); ?>:</b>
      <?php echo CHtml::encode($data->sid); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
      <?php echo CHtml::encode($data->type); ?>
      <br /> */ ?>

    <?php
    $this->widget('CStarRating', array(
        'name' => $data->to_id . $data->from_id . $data->sid,
        'model' => $data,
        'attribute' => 'puntuation',
        'minRating' => 1,
        'maxRating' => 5,
        'starCount' => 5,
        'resetText' => '0',
        'resetValue' => 0,
        'readOnly' => true,
    ));
    ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
<?php echo CHtml::encode($data->comment); ?>
    <br />

    <?php /*
      <b><?php echo CHtml::encode($data->getAttributeLabel('added')); ?>:</b>
      <?php echo CHtml::encode($data->added); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
      <?php echo CHtml::encode($data->updated); ?>
      <br />

     */ ?>

</div>
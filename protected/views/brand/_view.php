<?php
/* @var $this BrandController */
/* @var $data Brand */
?>
<div class="small-12 large-3 columns">
    <div class="view">
        <h5><?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id' => $data->id)); ?></h5>
        <?php if ($data->image != '') { ?>
            <img class="br_img" src="<?php echo Yii::app()->request->baseUrl . '/images/brands/' . Brand::THUMB_PREFIX . $data->image ?>" alt="<?php echo CHtml::encode($data->summary) ?>"/>
        <?php } ?>

        <?php echo CHtml::encode($data->summary); ?>
    </div>
</div>

<?php /*
  <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
  <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
  <?php echo CHtml::encode($data->name); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('summary')); ?>:</b>
  <?php echo CHtml::encode($data->summary); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
  <?php echo CHtml::encode($data->description); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('image')); ?>:</b>
  <?php echo CHtml::encode($data->image); ?>
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

  <b><?php echo CHtml::encode($data->getAttributeLabel('deleted')); ?>:</b>
  <?php echo CHtml::encode($data->deleted); ?>
  <br />

 */ ?>
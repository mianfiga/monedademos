<?php
/* @var $this BrandController */
/* @var $data Brand */
?>
<div class="small-12 large-3 columns">
    <div class="view br_block">
        <h5><?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id' => $data->id)); ?></h5>
        <div class="row transparent">
            <?php if ($data->image != '') { ?>
                <div class="small-5 large-12 columns">
                    <?php echo CHtml::link('<img class="br_img" src="'. Yii::app()->request->baseUrl . '/images/brands/' . Brand::THUMB_PREFIX . $data->image .'" alt="'. CHtml::encode($data->summary).'"/>', array('view', 'id' => $data->id)); ?>
                </div>
            <?php } ?>
            <div class="small-7 large-12 columns">
                <?php echo CHtml::encode($data->summary); ?>
            </div>
        </div>
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
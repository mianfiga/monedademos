<?php
/* @var $this NotificationController */
/* @var $data NotificationUser */
?>
<?php $cls= '';
      if($data->updated < $data->shown)
        $cls= ' nt_read';
      elseif ($data->updated > $data->read)
        $cls = ' nt_unread';
?>
<div class="view nt_view<?php echo $cls; ?>" onclick="location.href = '<?php echo $data->getUrl()?>'">
<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />
  
  <b><?php echo CHtml::encode($data->getAttributeLabel('notification_id')); ?>:</b>
	<?php echo CHtml::encode($data->notification_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->notification->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->notification->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->notification->getAttributeLabel('message')); ?>:</b>
  */?>
  <strong><?php echo Yii::t('notification',$data->notification->title); ?></strong>: 
	<?php
    
  //$array = get_object_vars(json_decode($data->data)[0]);
  //echo Yii::t('notification',$data->notification->message,$array); 
  echo $data->message();
  ?>
<?php /*	<b><?php echo CHtml::encode($data->getAttributeLabel('subject')); ?>:</b>
	<?php echo CHtml::encode($data->subject); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('view')); ?>:</b>
	<?php echo CHtml::encode($data->view); ?>
	<br />
*/ ?>

</div>
<?php $data->read(); ?>
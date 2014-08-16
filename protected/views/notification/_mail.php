<?php
/* @var $this NotificationController */
/* @var $data NotificationMessage */
?>
<?php $cls= '';
      if($data->updated < $data->shown)
        $cls= ' nt_read';
      elseif ($data->updated > $data->read)
        $cls = ' nt_unread';
?>
<div class="view nt_view<?php echo $cls; ?>">
  <strong><?php echo Yii::t('notification',$data->notification->title); ?></strong><br/> 
  <p><?php
  echo $data->message();
  ?></p>
  <?php echo CHtml::link($data->getUrl(),$data->getUrl());?><br/><br/><br/>
  
  Si no desea seguir recibiendo esta notificación al correo electrónico haga <a href="<?php echo utf8_encode (Yii::app()->createAbsoluteUrl('notification/unsubscribe', array('e_id' => $data->entity_id, 'n_id' => $data->notification_id, 'm' => $data->entity->getMagic()))) ?>">click aquí</a>, si en un futuro desea restablecerla o para cualquier otra consulta puede ponerse en contacto con nosotros en contacto@monedademos.es.
  
<?php /*	<b><?php echo CHtml::encode($data->getAttributeLabel('subject')); ?>:</b>
	<?php echo CHtml::encode($data->subject); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('view')); ?>:</b>
	<?php echo CHtml::encode($data->view); ?>
	<br />
*/ ?>

</div>

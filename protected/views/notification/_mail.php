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
<div class="view nt_view<?php echo $cls; ?>">
  <strong><?php echo Yii::t('notification',$data->notification->title); ?></strong><br/> 
  <p><?php
  echo $data->message();
  ?></p>
  <?php echo CHtml::link($data->getUrl(),$data->getUrl());?><br/><br/><br/>
  
  Estas notificaciones están bajo pruebas, si no desea recibir notificaciones al correo electrónico, o para cualquier otra consulta puede ponerse en contacto con nosotros en contacto@instauremoslademocracia.net.
  
<?php /*	<b><?php echo CHtml::encode($data->getAttributeLabel('subject')); ?>:</b>
	<?php echo CHtml::encode($data->subject); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('view')); ?>:</b>
	<?php echo CHtml::encode($data->view); ?>
	<br />
*/ ?>

</div>

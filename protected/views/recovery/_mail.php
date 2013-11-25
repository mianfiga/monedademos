<?php
/* @var $this NotificationController */
/* @var $user User */
?>
<div class="view nt_view">
  <strong><?php echo Yii::t('notification','Password recovery'); ?></strong><br/> 
  <p><?php
  echo Yii::t('notification','Click in the following link to set a new password');
  ?>:</p>
  <?php
  $url = Yii::app()->createAbsoluteUrl('user/recovery', array('id' => $user->id,'magic'=>$user->magic));
  echo CHtml::link($url,$url);?><br/><br/><br/>
  
  Estas notificaciones están bajo pruebas, si no desea recibir notificaciones al correo electrónico, o para cualquier otra consulta puede ponerse en contacto con nosotros en contacto@monedademos.es.
</div>

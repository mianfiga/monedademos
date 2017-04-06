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

  <p>Ahorrate estos e-mail y recibe las notificaciones de monedademos en tu móvil con <a href="https://telegram.org/">Telegram</a> la alternativa a Whatsapp Libre y comprometida con tu privacidad.</p>
  <p>Desde un dispositivo con Telegram instalado ve a <a href="<?php echo utf8_encode (Yii::app()->createAbsoluteUrl('user/index');?>">tu usuario de monedademos</a> y haz clic en "Connect to Telegram" en el menú o
 visita <a href="https://telegram.me/monedademos_bot">@monedademos_bot</a>. No olvides activar en el bot las notificaciones del market para que no te pierdas nada escribiendo en el bot /market_on.</p>

  <p>Si no desea seguir recibiendo esta notificación al correo electrónico haga <a href="<?php echo utf8_encode (Yii::app()->createAbsoluteUrl('notification/unsubscribe', array('e_id' => $data->entity_id, 'n_id' => $data->notification_id, 'm' => $data->entity->getMagic()))) ?>">click aquí</a>, si en un futuro desea restablecerla o para cualquier otra consulta puede ponerse en contacto con nosotros en contacto@monedademos.es.</p>
</div>

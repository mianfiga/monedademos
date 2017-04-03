<?php
if($success){
  echo Yii::t('apiTelegram','Done! you have already connected Telegran & MonedaDemos.');
}else{
  echo Yii::t('apiTelegram','Something went wrong, try connecting to Telegram following this link:');
  echo '<a href="https://telegram.me/monedademos_bot?start='. $entity->id . '-' . $entity->getMagic() .'">' . Yii::t('app', 'Connect to Telegram') .'</a>';
}

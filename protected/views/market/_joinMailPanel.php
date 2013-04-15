<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <h1><?php echo $title; ?></h1>
    <b><?php echo Yii::t('market','New comment');?>:</b>
    <p><?php echo $message; ?></p>
    <h3><?php echo Yii::t('market','Reply at: {link}',array(
        '{link}' => CHtml::link(Yii::app()->createAbsoluteUrl('market/PanelUser',array('ad_id' => $ad_id, 'user_id' => $user_id)),
                Yii::app()->createAbsoluteUrl('market/PanelUser',array('ad_id' => $ad_id, 'user_id' => $user_id))))); ?></h3>


  </body>
</html>

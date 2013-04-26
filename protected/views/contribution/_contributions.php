<h3><?php echo Yii::t('app','Client contribution')?></h3>
<?php
	$client = Authorization::splitAccountNumber($model->getChargeAccountNumber());
	echo $this->renderPartial('//contribution/_view', array('data'=>User::model()->findByPk($client['entity_id'])));
?>

<h3><?php echo Yii::t('app','Vendor contribution')?></h3>
<?php
	$vendor = Authorization::splitAccountNumber($model->getDepositAccountNumber());
	echo $this->renderPartial('//contribution/_view', array('data'=>User::model()->findByPk($vendor['entity_id'])));
?>
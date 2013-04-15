		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'DEMOS'),
				array('label'=>Yii::t('app','Menu'), 'url'=>array('/site/menu')),
				array('label'=>Yii::t('app','Account'), 'url'=>array('/transaction/index'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=> Yii::t('app','Contributions'), 'url'=>array('/contribution/index')),
        array('label'=>Yii::t('app','Market'), 'url'=>array('market/index'), 'visible'=>true),
				array('label'=>Yii::t('app','Login'),'url'=>array('/site/login'),'visible'=>Yii::app()->user->isGuest),
				array('label'=>Yii::t('app','Logout'), 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
			),
		)); ?>

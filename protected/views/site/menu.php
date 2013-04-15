		<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Operations',
		));


		$this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=> Yii::t('app','Home'), 'url'=>array('/site/index')),
				array('label'=> Yii::t('app','About'), 'url'=>array('/site/page', 'view'=>'about')),
//				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=> Yii::t('app','Account'), 'url'=>array('/transaction/index'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=> Yii::t('app','Contributions'), 'url'=>array('/contribution/index')),
        array('label'=>Yii::t('app','Market'), 'url'=>array('market/index'), 'visible'=>true),
				array('label'=> Yii::t('app','Invite friend'), 'url'=>array('/invitation/index'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=> Yii::t('app','Join now'), 'url'=>array('/user/create'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=> Yii::t('app','User ({user})',array('{user}'=>Yii::app()->user->name)), 'url'=>array('/user/view','id'=>Yii::app()->user->id), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=> Yii::t('app','Login'),'url'=>array('/site/login'),'visible'=>Yii::app()->user->isGuest),
				array('label'=> Yii::t('app','Logout'), 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
			),
		));
		$this->endWidget(); ?>

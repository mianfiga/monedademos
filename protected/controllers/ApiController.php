<?php

class ApiController extends Controller
{
	public function actionTelegram($u)
	{
		$request = Yii::app()->getRequest();
		$ip_telegram = array(
			'149.154.167.197', '149.154.167.198', '149.154.167.199', '149.154.167.200',
			'149.154.167.201', '149.154.167.202', '149.154.167.203', '149.154.167.204',
			'149.154.167.205', '149.154.167.206', '149.154.167.207', '149.154.167.208',
			'149.154.167.209', '149.154.167.210', '149.154.167.211', '149.154.167.212',
			'149.154.167.213', '149.154.167.214', '149.154.167.215', '149.154.167.216',
			'149.154.167.217', '149.154.167.218', '149.154.167.219', '149.154.167.220',
			'149.154.167.221', '149.154.167.222', '149.154.167.223', '149.154.167.224',
			'149.154.167.225', '149.154.167.226', '149.154.167.227', '149.154.167.228',
			'149.154.167.229', '149.154.167.230', '149.154.167.231', '149.154.167.232',
			'149.154.167.233'
		);
		//https://api.telegram.org/bot<BOT_ID>/setwebhook?url=https%3A%2F%2Fmonedademos.es%2Findex.php%3Fr%3Dapi%2Ftelegram%26u%3D<telegram_url_uglyfier>
		if (Yii::app()->params['telegram_url_uglyfier'] != $u && !in_array ( $request->getUserHostAddress() , $ip_telegram)){
			throw new CHttpException(404, 'Access denied.');
		}
		$api_url = 'https://api.telegram.org/bot'. Yii::app()->params['telegram_token'] .'/';
		$content = file_get_contents('php://input');
		$update = json_decode($content, true);
		if(isset($update['message'])){ //chat mode
			$chat_id = $update['message']['chat']['id'];
			$text = $update['message']['text'];

			$sendto =$api_url . 'sendmessage?chat_id=' .$chat_id. '&text=' . $update['message']['text'];
			//$update['message']
			if($text[0] == '/'){ //command
				$command = explode(' ', $text);
				switch($command[0]){
					case '/start':
						$entity_sid=$command[1];
						if($entity_sid){
							$sid = explode('-', $entity_sid);
							$entity = Entity::model()->findByPk($sid[0]);
							if($entity && $entity->magic == $sid[1]){
								ApiTelegram::updateRecord($entity->id, $chat_id, $update['message']['from']['id'] ,(isset($update['message']['from']['username'])?$update['message']['from']['username']:''));
							}
							Yii::app()->setLanguage($entity->getCulture());
						}
						$sendto =$api_url . 'sendmessage?chat_id=' .$chat_id. '&text=' . Yii::t('apiTelegram', 'Welcome to Moneda Demos\' bot');
						break;
					default:
				}
			}
			file_get_contents($sendto);

		}else if(isset($update["inline_query"])){//inline mode

		}

		$this->render('telegram');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}

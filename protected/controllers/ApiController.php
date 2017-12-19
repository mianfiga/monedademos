<?php

class ApiController extends Controller
{
	public function filters() {
			return array(
					'accessControl', // perform access control for CRUD operations
			);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
			return array(
					array('allow', // allow all users to perform 'index' and 'view' actions
							'actions' => array('telegram'),
							'users' => array('*'),
					),
					array('allow', // allow authenticated user to perform 'create' and 'update' actions
							'actions' => array('connectTelegram'),
							'users' => array('@'),
					),
					array('allow', // allow admin user to perform 'admin' and 'delete' actions
							'actions' => array('index'),
							'users' => array('admin'),
					),
					array('deny', // deny all users
							'users' => array('*'),
					),
			);
	}
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

			$answer = $update['message']['text'];
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
							ApiTelegram::sendMessage($chat_id, Yii::t('apiTelegram', 'Welcome to Moneda Demos\' bot'));
							return;
						}else{
							ApiTelegram::sendMessage($chat_id, Yii::t('apiTelegram', 'Welcome to Moneda Demos\' bot'));
							ApiTelegram::sendMessage($chat_id, Yii::t('apiTelegram', 'We need you connect this bot to your monedademos\' user. You can do it by following this link:'));
							ApiTelegram::sendMessage($chat_id, 'https://monedademos.es/index.php?r=api/connectTelegram&c='. $chat_id . '&u='.  $update['message']['from']['id'] . (isset($update['message']['from']['username'])?'&un='.$update['message']['from']['username']:''));
						}
						$this->render('telegram');
						return;
					case '/market_on':
						$apiTelegram = ApiTelegram::model()->with('entity')->findByAttributes(array('chat_id'=> $update['message']['chat']['id']));
						Yii::app()->setLanguage($apiTelegram->entity->getCulture());
						if(ApiTelegram::setMarketNotifications($chat_id, true)){
							ApiTelegram::sendMessage($chat_id, Yii::t('apiTelegram', 'Market notifications ON'));
						}else{
							ApiTelegram::sendMessage($chat_id, Yii::t('apiTelegram', 'Something failed'));
						}
						$this->render('telegram');
						return;
					case '/market_off':
						$apiTelegram = ApiTelegram::model()->with('entity')->findByAttributes(array('chat_id'=> $update['message']['chat']['id']));
						Yii::app()->setLanguage($apiTelegram->entity->getCulture());
						if(ApiTelegram::setMarketNotifications($chat_id, false)){
							ApiTelegram::sendMessage($chat_id, Yii::t('apiTelegram', 'Market notifications OFF'));
						}else{
							ApiTelegram::sendMessage($chat_id, Yii::t('apiTelegram', 'Something failed'));
						}
						$this->render('telegram');
						return;
					default:
				}
			}
			ApiTelegram::sendMessage($chat_id, $answer);

		}else if(isset($update['inline_query'])){//inline mode

		}else if(isset($update['callback_query'])){//inline mode
			$callback_query_id=$update["callback_query"]['id'];
			$game = $update["callback_query"]['game_short_name'];
			switch($game){
					case 'Pay':
						ApiTelegram::answerCallbackQuery($callback_query_id, '', false, 'https://monedademos.es/api_telegram_bot_pay.html');
						break;
					default:
						ApiTelegram::answerCallbackQuery($callback_query_id, 'Sorry I couldn\'t do anything', true, '');
			}
		}else{

		}

		$this->render('telegram');
	}

	public function actionConnectTelegram($c,$u){
		$id = Yii::app()->user->getId();
		$un = Yii::app()->request->getQuery('un');
		$entity = Entity::model()->findByPk($id);
		if ($entity === null) {
				throw new CHttpException(404, 'The requested page does not exist.');
		}
		$success = ApiTelegram::updateRecord($id, $c, $u, $un);
		ApiTelegram::sendMessage($c,Yii::t('apiTelegram','You have conected this bot to you monedademos user'));
		$this->render('connectTelegram', array(
				'success' => $success,
				'entity' => $entity
		));
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

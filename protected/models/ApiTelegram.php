<?php

/**
 * This is the model class for table "{{api_telegram}}".
 *
 * The followings are the available columns in table '{{api_telegram}}':
 * @property string $entity_id
 * @property string $chat_id
 * @property string $user_id
 * @property string $username
 * @property string $last_action
 * @property string $added
 * @property string $updated
 * @property string $deleted
 *
 * The followings are the available model relations:
 * @property Entity $entity
 */
class ApiTelegram extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{api_telegram}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('entity_id, chat_id, user_id', 'required'),
			array('entity_id', 'length', 'max'=>11),
			array('chat_id, user_id, username', 'length', 'max'=>64),
			array('last_action, added, updated, deleted', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('entity_id, chat_id, user_id, username, last_action, added, updated, deleted', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'entity' => array(self::BELONGS_TO, 'Entity', 'entity_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'entity_id' => 'Entity',
			'chat_id' => 'Chat',
			'user_id' => 'User',
			'username' => 'Username',
			'last_action' => 'Last Action',
			'added' => 'Added',
			'updated' => 'Updated',
			'deleted' => 'Deleted',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('entity_id',$this->entity_id,true);
		$criteria->compare('chat_id',$this->chat_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('last_action',$this->last_action,true);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('deleted',$this->deleted,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ApiTelegram the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function updateRecord($entity_id, $chat_id, $user_id, $username='') {
			$record = self::model()->findByPk($entity_id);
			$date = Common::datetime();
			if(!$record){
					$record = new ApiTelegram;
					$record->entity_id= $entity_id;
					$record->last_action= $date;
					$record->added = $date;
			}
			$record->chat_id = $chat_id;
			$record->user_id = $user_id;
			$record->username = $username;
			return $record->save();
	}
	public static function sendMessage($chat_id, $text){
		return self::doSend('sendmessage?chat_id=' .$chat_id. '&text=' . urlencode ($text));
	}
	public static function answerCallbackQuery($callback_query_id, $text='', $show_alert=false, $url){
		$sendto ='answerCallbackQuery?callback_query_id=' . $callback_query_id . '&text=' . urlencode ($text) .'&show_alert='.$show_alert.'&url='.urlencode ($url);
		return self::doSend($sendto);
	}

	public static function doSend($sendto){
		$api_url = 'https://api.telegram.org/bot'. Yii::app()->params['telegram_token'] .'/';
		$message = $api_url . $sendto;
		$result = file_get_contents($message);
		$data_result = json_decode($result,true);
		return isset($data_result['ok']) && $data_result['ok'];
	}

	public static function setMarketNotifications($chat_id, $active) {
			$record = self::model()->findByAttributes(array(
				'chat_id' => $chat_id
			));
			if(!record){
				return false;
			}
			$record->market_notifications = $active;
			$date = Common::datetime();
			$record->last_action = $date;
			return $record->save();
	}
}

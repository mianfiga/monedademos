<?php

/**
 * This is the model class for table "{{notification}}".
 *
 * The followings are the available columns in table '{{notification}}':
 * @property string $id
 * @property string $title
 * @property string $message
 * @property string $subject
 * @property string $view
 *
 * The followings are the available model relations:
 * @property User[] $rbuUsers
 */
class Notification extends NotificationBase
{
    const EXPIRATION = 432000; //5 días(86400×5)
  	const PAYMENT = 1;
    const CHARGE = 2;
    const SALARY = 3;
    const TAX = 4;
    const PENDING = 5;
    const ADVICE = 6;
    const MARKET_JOINED = 7;
		const MARKET_JOINED_COMM = 8;
		const MARKET_AD_EXPIRATION = 9;
    const MARKET_AD_EXPIRED = 10;
		const MARKET_CREATOR_COMM = 11;
		const MARKET_AD_STATUS = 12;
    const CONTRIBUTION_COMM = 13;
    const SYSTEM = 14;


	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NotificationBase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{notification}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, subject, view', 'length', 'max'=>127),
			array('message', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, message, subject, view', 'safe', 'on'=>'search'),
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
			'users' => array(self::MANY_MANY, 'User', '{{notification_configuration}}(notification_id, user_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'message' => 'Message',
			'subject' => 'Subject',
			'view' => 'View',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('view',$this->view,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
  
  public static function getSID($object)
  {
    switch(get_class($object))
    {
      case 'Transaction':
        return 'tr-'.$object->id;
      case 'Pending':
        return 'pe-'.$object->id;
      case 'MarketAd':
        return 'ad-'.$object->id;
      case 'MarketJoined':
        return 'jo-'.$object->ad_id.'-'.$object->user_id;
    }
  }
  public static function getObject($SID)
  {
    if ($SID == null)
      return null;
      
    $data = explode('-',$SID);
    switch($data[0])
    {
      case 'tr':
        return Transaction::model()->findByPk($data[1]);
      case 'pe':
        return Pending::model()->findByPk($data[1]);
      case 'ad':
        return MarketAd::model()->findByPk($data[1]);
      case 'jo':
        return MarketJoined::model()->findByPk(array('ad_id' => $data[1], 'user_id'=>$data[2]));
    }
  }
  
  public static function addNotification($notification_id, $user_id, $SID, $data)
  {
    $notif_user = NotificationUser::model()->findByPk(array('notification_id' => $notification_id, 'user_id' => $user_id, 'sid' => $SID));
    if($notif_user === null)
    {
      $notif_user = new NotificationUser;
      $notif_user->notification_id = $notification_id;
      $notif_user->user_id =$user_id;
      $notif_user->sid = $SID;
    }
    $pre_data = ($notif_user->data!=null?json_decode($notif_user->data,true):array());
    reset($data);
    if(is_numeric(key($data)))
    {
      $pre_data = array_merge($pre_data, $data);
    }
    else
    {
      array_unshift($pre_data, $data);
    }
    $notif_user->data = json_encode($pre_data);
    $notif_user->save();
  }
  
  public static function removeNotification($notification_id, $user_id, $SID, $data=null)
  {
    $notif_user = NotificationUser::model()->findByPk(array('notification_id' => $notification_id, 'user_id' => $user_id, 'sid' => $SID));
    if($notif_user === null)
      return true;
    
    if ($data !== null)
    {
      $pre_data = ($notif_user->data!=null?json_decode($notif_user->data):array());
      
      foreach ($data as $key => $value)
      {
        unset($pre_data[$key]);
      }
      $notif_user->data = json_encode($pre_data);
      return $notif_user->save();
    }
    
    return $notif_user->delete();
  }
  
  public static function shown($user_id, $SID)
  {
    $notifs = NotificationUser::model()->findAllByAttributes(array('user_id' => $user_id, 'sid' => $SID));
    foreach ($notifs as $notif)
    {
      $notif->saveAttributes(array('shown'=>date('YmdHis')));
    }
  }
  public static function notify()
  {
    
    $notifs = NotificationUser::model()->findAll(
            array('condition'=> 't.`sent` < t.`updated` AND t.`read` < t.`updated` AND t.`shown` < t.`updated`',
                  'with' => array('notification' => array('together' => true),
                                  'user' => array('together' => true),
//                                  'configuration' => array('together' => true),
                      ),
                 ));
    
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= "From: noreply@instauremoslademocracia.net\r\n";
    
    foreach ($notifs as $notif)
    {
      $configuration = NotificationConfiguration::model()->find("notification_id=$notif->notification_id AND (user_id=$notif->user_id OR user_id=1) ORDER BY user_id DESC");

      $udate =  strtotime($notif->sent); //date_timestamp_get(DateTime::createFromFormat('Y-m-d H:i:s',$notif->sent));
      
      if($configuration->mailmode == NotificationConfiguration::MAILMODE_INSTANTLY 
            || ($configuration->mailmode == NotificationConfiguration::MAILMODE_DAILY &&
                ($udate + 86400) < time() ))
      {
        Yii::app()->setLanguage($notif->user->culture);
        if(mail($notif->user->email,$notif->subject(),
                   CController::renderInternal(Yii::getPathOfAlias('application.views').($notif->notification->view!=''?$notif->notification->view:'/notification/_mail').'.php', array('data'=>$notif), true),$headers))
        {
          $notif->sent = date('YmdHis');
        }
      }

      $notif->setScenario('notify');
      $notif->save();
    }
  }
  
}

<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property string $id
 * @property string $username
 * @property string $salt
 * @property string $password
 * @property string $name
 * @property string $surname
 * @property string $birthday
 * @property string $identification
 * @property string $contribution_title
 * @property string $contribution_text
 * @property string $email
 * @property string $contact
 * @property string $zip
 * @property string $culture
 * @property string $exemption_id
 * @property string $created
 * @property string $created_by
 * @property string $last_login
 * @property string $last_action
 * @property string $updated
 * @property string $blocked
 * @property string $deleted
 *
 * The followings are the available model relations:
 * @property Account[] $rbuAccounts
 * @property MarketAd[] $marketAds
 * @property MarketAd[] $rbuMarketAds
 * @property Notification[] $rbuNotifications
 * @property NotificationUser[] $notificationUsers
 * @property Pending[] $pendings
 * @property Pending[] $pendings1
 * @property Transaction[] $transactions
 * @property Transaction[] $transactions1
 * @property Exemption $exemption
 * @property UserBase $createdBy
 * @property UserBase[] $users
 */
class UserBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserBase the static model class
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
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, salt, password, name, surname, identification, contribution_title, contribution_text, email, contact, zip, updated', 'required'),
			array('username, salt, password', 'length', 'max'=>128),
			array('name', 'length', 'max'=>127),
			array('surname, contribution_title, email', 'length', 'max'=>255),
			array('zip', 'length', 'max'=>16),
			array('culture', 'length', 'max'=>7),
			array('exemption_id, created_by', 'length', 'max'=>10),
			array('birthday, created, last_login, last_action, blocked, deleted', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, salt, password, name, surname, birthday, identification, contribution_title, contribution_text, email, contact, zip, culture, exemption_id, created, created_by, last_login, last_action, updated, blocked, deleted', 'safe', 'on'=>'search'),
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
			'rbuAccounts' => array(self::MANY_MANY, 'Account', '{{authorization}}(user_id, account_id)'),
			'marketAds' => array(self::HAS_MANY, 'MarketAd', 'created_by'),
			'rbuMarketAds' => array(self::MANY_MANY, 'MarketAd', '{{market_joined}}(user_id, ad_id)'),
			'rbuNotifications' => array(self::MANY_MANY, 'Notification', '{{notification_configuration}}(user_id, notification_id)'),
			'notificationUsers' => array(self::HAS_MANY, 'NotificationUser', 'user_id'),
			'pendings' => array(self::HAS_MANY, 'Pending', 'charge_user'),
			'pendings1' => array(self::HAS_MANY, 'Pending', 'deposit_user'),
			'transactions' => array(self::HAS_MANY, 'Transaction', 'charge_user'),
			'transactions1' => array(self::HAS_MANY, 'Transaction', 'deposit_user'),
			'exemption' => array(self::BELONGS_TO, 'Exemption', 'exemption_id'),
			'createdBy' => array(self::BELONGS_TO, 'UserBase', 'created_by'),
			'users' => array(self::HAS_MANY, 'UserBase', 'created_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'salt' => 'Salt',
			'password' => 'Password',
			'name' => 'Name',
			'surname' => 'Surname',
			'birthday' => 'Birthday',
			'identification' => 'Identification',
			'contribution_title' => 'Contribution Title',
			'contribution_text' => 'Contribution Text',
			'email' => 'Email',
			'contact' => 'Contact',
			'zip' => 'Zip',
			'culture' => 'Culture',
			'exemption_id' => 'Exemption',
			'created' => 'Created',
			'created_by' => 'Created By',
			'last_login' => 'Last Login',
			'last_action' => 'Last Action',
			'updated' => 'Updated',
			'blocked' => 'Blocked',
			'deleted' => 'Deleted',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('identification',$this->identification,true);
		$criteria->compare('contribution_title',$this->contribution_title,true);
		$criteria->compare('contribution_text',$this->contribution_text,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('contact',$this->contact,true);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('culture',$this->culture,true);
		$criteria->compare('exemption_id',$this->exemption_id,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('created_by',$this->created_by,true);
		$criteria->compare('last_login',$this->last_login,true);
		$criteria->compare('last_action',$this->last_action,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('blocked',$this->blocked,true);
		$criteria->compare('deleted',$this->deleted,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
<?php

/**
 * This is the model class for table "{{entity}}".
 *
 * The followings are the available columns in table '{{entity}}':
 * @property string $id
 * @property string $class
 * @property string $object_id
 * @property integer $points
 * @property integer $rates
 *
 * The followings are the available model relations:
 * @property ActivityLog[] $activityLogs
 * @property Account[] $rbuAccounts
 * @property Brand[] $brands
 * @property MarketAd[] $marketAds
 * @property MarketAd[] $rbuMarketAds
 * @property Notification[] $rbuNotifications
 * @property NotificationMessage[] $notificationMessages
 * @property Pending[] $pendings
 * @property Pending[] $pendings1
 * @property Rate[] $rates0
 * @property Rate[] $rates01
 * @property Role[] $roles
 * @property Role[] $roles1
 * @property Transaction[] $transactions
 * @property Transaction[] $transactions1
 */
class EntityBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EntityBase the static model class
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
		return '{{entity}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('class, object_id', 'required'),
			array('points, rates', 'numerical', 'integerOnly'=>true),
			array('class', 'length', 'max'=>32),
			array('object_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, class, object_id, points, rates', 'safe', 'on'=>'search'),
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
			'activityLogs' => array(self::HAS_MANY, 'ActivityLog', 'entity_id'),
			'rbuAccounts' => array(self::MANY_MANY, 'Account', '{{authorization}}(entity_id, account_id)'),
			'brands' => array(self::HAS_MANY, 'Brand', 'created_by'),
			'marketAds' => array(self::HAS_MANY, 'MarketAd', 'created_by'),
			'rbuMarketAds' => array(self::MANY_MANY, 'MarketAd', '{{market_joined}}(entity_id, ad_id)'),
			'rbuNotifications' => array(self::MANY_MANY, 'Notification', '{{notification_configuration}}(entity_id, notification_id)'),
			'notificationMessages' => array(self::HAS_MANY, 'NotificationMessage', 'entity_id'),
			'pendings' => array(self::HAS_MANY, 'Pending', 'charge_entity'),
			'pendings1' => array(self::HAS_MANY, 'Pending', 'deposit_entity'),
			'rates0' => array(self::HAS_MANY, 'Rate', 'from_id'),
			'rates01' => array(self::HAS_MANY, 'Rate', 'to_id'),
			'roles' => array(self::HAS_MANY, 'Role', 'actor_id'),
			'roles1' => array(self::HAS_MANY, 'Role', 'part_id'),
			'transactions' => array(self::HAS_MANY, 'Transaction', 'charge_entity'),
			'transactions1' => array(self::HAS_MANY, 'Transaction', 'deposit_entity'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'class' => 'Class',
			'object_id' => 'Object',
			'points' => 'Points',
			'rates' => 'Rates',
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
		$criteria->compare('class',$this->class,true);
		$criteria->compare('object_id',$this->object_id,true);
		$criteria->compare('points',$this->points);
		$criteria->compare('rates',$this->rates);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
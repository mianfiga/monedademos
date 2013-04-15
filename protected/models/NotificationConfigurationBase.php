<?php

/**
 * This is the model class for table "{{notification_configuration}}".
 *
 * The followings are the available columns in table '{{notification_configuration}}':
 * @property string $user_id
 * @property string $notification_id
 * @property string $mailmode
 * @property string $webmode
 * @property string $pushmode
 */
class NotificationConfigurationBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NotificationConfigurationBase the static model class
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
		return '{{notification_configuration}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, notification_id', 'required'),
			array('user_id, notification_id', 'length', 'max'=>10),
			array('mailmode', 'length', 'max'=>9),
			array('webmode, pushmode', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, notification_id, mailmode, webmode, pushmode', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'notification_id' => 'Notification',
			'mailmode' => 'Mailmode',
			'webmode' => 'Webmode',
			'pushmode' => 'Pushmode',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('notification_id',$this->notification_id,true);
		$criteria->compare('mailmode',$this->mailmode,true);
		$criteria->compare('webmode',$this->webmode,true);
		$criteria->compare('pushmode',$this->pushmode,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
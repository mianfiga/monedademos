<?php

/**
 * This is the model class for table "{{notification_message}}".
 *
 * The followings are the available columns in table '{{notification_message}}':
 * @property string $entity_id
 * @property string $notification_id
 * @property string $sid
 * @property string $data
 * @property string $added
 * @property string $sent
 * @property string $read
 * @property string $updated
 * @property string $shown
 *
 * The followings are the available model relations:
 * @property Entity $entity
 * @property Notification $notification
 */
class NotificationMessageBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NotificationMessageBase the static model class
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
		return '{{notification_message}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('entity_id, notification_id', 'required'),
			array('entity_id', 'length', 'max'=>11),
			array('notification_id', 'length', 'max'=>10),
			array('sid', 'length', 'max'=>127),
			array('data, added, sent, read, updated, shown', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('entity_id, notification_id, sid, data, added, sent, read, updated, shown', 'safe', 'on'=>'search'),
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
			'notification' => array(self::BELONGS_TO, 'Notification', 'notification_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'entity_id' => 'Entity',
			'notification_id' => 'Notification',
			'sid' => 'Sid',
			'data' => 'Data',
			'added' => 'Added',
			'sent' => 'Sent',
			'read' => 'Read',
			'updated' => 'Updated',
			'shown' => 'Shown',
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

		$criteria->compare('entity_id',$this->entity_id,true);
		$criteria->compare('notification_id',$this->notification_id,true);
		$criteria->compare('sid',$this->sid,true);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('sent',$this->sent,true);
		$criteria->compare('read',$this->read,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('shown',$this->shown,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

}

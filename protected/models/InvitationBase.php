<?php

/**
 * This is the model class for table "{{invitation}}".
 *
 * The followings are the available columns in table '{{invitation}}':
 * @property string $id
 * @property string $user_id
 * @property string $note
 * @property string $code
 * @property string $created
 * @property string $sent
 * @property string $used
 *
 * The followings are the available model relations:
 * @property User $user
 */
class InvitationBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return InvitationBase the static model class
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
		return '{{invitation}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, code', 'required'),
			array('user_id', 'length', 'max'=>10),
			array('note', 'length', 'max'=>127),
			array('code', 'length', 'max'=>8),
			array('created, sent, used', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, note, code, created, sent, used', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'note' => 'Note',
			'code' => 'Code',
			'created' => 'Created',
			'sent' => 'Sent',
			'used' => 'Used',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('sent',$this->sent,true);
		$criteria->compare('used',$this->used,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
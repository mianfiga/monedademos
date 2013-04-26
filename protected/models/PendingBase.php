<?php

/**
 * This is the model class for table "{{pending}}".
 *
 * The followings are the available columns in table '{{pending}}':
 * @property string $id
 * @property string $executed_at
 * @property string $class
 * @property string $amount
 * @property string $charge_account
 * @property string $deposit_account
 * @property string $charge_entity
 * @property string $deposit_entity
 * @property string $subject
 *
 * The followings are the available model relations:
 * @property Entity $depositEntity
 * @property Account $chargeAccount
 * @property Account $depositAccount
 * @property Entity $chargeEntity
 */
class PendingBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PendingBase the static model class
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
		return '{{pending}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('class, amount, charge_account, deposit_account, charge_entity, deposit_entity', 'required'),
			array('class', 'length', 'max'=>8),
			array('amount', 'length', 'max'=>20),
			array('charge_account, deposit_account', 'length', 'max'=>10),
			array('charge_entity, deposit_entity', 'length', 'max'=>11),
			array('subject', 'length', 'max'=>255),
			array('executed_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, executed_at, class, amount, charge_account, deposit_account, charge_entity, deposit_entity, subject', 'safe', 'on'=>'search'),
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
			'depositEntity' => array(self::BELONGS_TO, 'Entity', 'deposit_entity'),
			'chargeAccount' => array(self::BELONGS_TO, 'Account', 'charge_account'),
			'depositAccount' => array(self::BELONGS_TO, 'Account', 'deposit_account'),
			'chargeEntity' => array(self::BELONGS_TO, 'Entity', 'charge_entity'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'executed_at' => 'Executed At',
			'class' => 'Class',
			'amount' => 'Amount',
			'charge_account' => 'Charge Account',
			'deposit_account' => 'Deposit Account',
			'charge_entity' => 'Charge Entity',
			'deposit_entity' => 'Deposit Entity',
			'subject' => 'Subject',
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
		$criteria->compare('executed_at',$this->executed_at,true);
		$criteria->compare('class',$this->class,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('charge_account',$this->charge_account,true);
		$criteria->compare('deposit_account',$this->deposit_account,true);
		$criteria->compare('charge_entity',$this->charge_entity,true);
		$criteria->compare('deposit_entity',$this->deposit_entity,true);
		$criteria->compare('subject',$this->subject,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
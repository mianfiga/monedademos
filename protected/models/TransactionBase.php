<?php

/**
 * This is the model class for table "{{transaction}}".
 *
 * The followings are the available columns in table '{{transaction}}':
 * @property string $id
 * @property string $executed_at
 * @property string $class
 * @property string $amount
 * @property string $charge_account
 * @property string $deposit_account
 * @property string $charge_user
 * @property string $deposit_user
 * @property string $subject
 *
 * The followings are the available model relations:
 * @property Account $chargeAccount
 * @property Account $depositAccount
 * @property User $chargeUser
 * @property User $depositUser
 */
class TransactionBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TransactionBase the static model class
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
		return '{{transaction}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('class, amount, charge_account, deposit_account, charge_user, deposit_user', 'required'),
			array('class', 'length', 'max'=>8),
			array('amount', 'length', 'max'=>20),
			array('charge_account, deposit_account, charge_user, deposit_user', 'length', 'max'=>10),
			array('subject', 'length', 'max'=>255),
			array('executed_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, executed_at, class, amount, charge_account, deposit_account, charge_user, deposit_user, subject', 'safe', 'on'=>'search'),
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
			'chargeAccount' => array(self::BELONGS_TO, 'Account', 'charge_account'),
			'depositAccount' => array(self::BELONGS_TO, 'Account', 'deposit_account'),
			'chargeUser' => array(self::BELONGS_TO, 'User', 'charge_user'),
			'depositUser' => array(self::BELONGS_TO, 'User', 'deposit_user'),
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
			'charge_user' => 'Charge User',
			'deposit_user' => 'Deposit User',
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
		$criteria->compare('charge_user',$this->charge_user,true);
		$criteria->compare('deposit_user',$this->deposit_user,true);
		$criteria->compare('subject',$this->subject,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
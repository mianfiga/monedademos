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
class Pending extends PendingBase
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
			'executed_at' => Yii::t('app','Executed At'),
			'class' => Yii::t('app','Class'),
			'amount' => Yii::t('app','Amount'),
			'charge_account' => Yii::t('app','Charge Account'),
			'deposit_account' => Yii::t('app','Deposit Account'),
			'charge_user' => Yii::t('app','Charge User'),
			'deposit_user' => Yii::t('app','Deposit User'),
			'subject' => Yii::t('app','Subject'),
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
  
	public function getChargeAccountNumber()
	{
		return Authorization::formAccountNumber($this->charge_user, $this->charge_account);
	}

	public function getDepositAccountNumber()
	{
		return Authorization::formAccountNumber($this->deposit_user, $this->deposit_account);
	}

	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$this->executed_at = date('YmdHis');
      }
      return true;
    }
    else
      return false;
  }

  protected function afterSave()
	{
    parent::afterSave();
    
    $user_id = Yii::app()->user->getId();
    $notif_data = array('{amount}' => Transaction::amountSystemToUser($this->amount),
                        '{charge_account_number}' => $this->getChargeAccountNumber(),
                        '{charge_user_name}' => $this->chargeUser->name,
                        '{deposit_account_number}' => $this->getDepositAccountNumber(),
                        '{deposit_user_name}' => $this->depositUser->name,
                        '{subject}' => $this->subject);
    Notification::addNotification(Notification::PENDING,$this->charge_user,Notification::getSID($this),$notif_data);

	}
  
  protected function afterDelete() 
  { 
    parent::afterSave();
    Notification::removeNotification(Notification::PENDING, $this->charge_user,Notification::getSID($this));
  }

}

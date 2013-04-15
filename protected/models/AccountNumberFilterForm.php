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
class AccountNumberFilterForm extends CFormModel
{
		public $account_number;

  public function afterConstruct()
  {
    $this->account_number = Yii::app()->session['accountNumber'];         
  }
  
	public function rules()
	{
		return array(
			array('account_number', 'required'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'account_number'=> Yii::t('app','Select another account'),
		);
	}
}

<?php

/**
 * ConfirmForm class.
 * ConfirmForm is the data structure for confirming users account operations.
 * It is used by the 'confirm' action of 'transactionController'.
 */
class ConfirmForm extends CFormModel
{
	const ATTEMPTS = 10;
	public $sid;
	public $password;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// sid and password are required
			array('sid, password', 'required'),
			// password needs to be authenticated
			array('password', 'validatePassword'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'password'=>Yii::t('app','Client\'s Pin/Password'),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function validatePassword($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			switch(Yii::app()->session['operations'][$this->sid]['action'])
			{
				case 'transfer':
				case 'charge':
				case 'movement':
					$model = Yii::app()->session['operations'][$this->sid]['model'];
					$auth = Authorization::model()->findByPk(array('entity_id' => $model['charge_entity'],
						'account_id' => $model['charge_account']));
					if($auth->wrong_pass_count >= self::ATTEMPTS)
					{
						$this->addError('password','Sorry, this account was already blocked due to many passwords attempts.');
						return;
					}

					if(!(self::encodedPassword($this->password,$auth->salt)===$auth->password))
					{
//						$auth->wrong_pass_count = $auth->wrong_pass_count + 1;
						$auth->saveAttributes(array('wrong_pass_count' => $auth->wrong_pass_count + 1));
						$this->addError('password','Incorrect Pin/Password.');
						return;
					}
					
				 break;
			}
		}
	}

	public static function encodedPassword($password,$salt)
	{
		return md5($salt.$password);
	}

}

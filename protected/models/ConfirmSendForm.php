<?php

/**
 * ConfirmForm class.
 * ConfirmForm is the data structure for confirming users account operations.
 * It is used by the 'confirm' action of 'transactionController'.
 */
class ConfirmSendForm extends CFormModel
{
	public $sid;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// sid and password are required
			array('sid', 'required'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
//			'password'=>Yii::t('app','Pin/Password'),
		);
	}
}

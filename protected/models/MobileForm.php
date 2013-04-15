<?php

/**
 * ConfirmForm class.
 * ConfirmForm is the data structure for confirming users account operations.
 * It is used by the 'confirm' action of 'transactionController'.
 */
class MobileForm extends CFormModel
{
	public $mobile;
	public $url;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('mobile', 'safe'),
			array('url', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'mobile'=>Yii::t('app','Mobile version'),
		);
	}
}

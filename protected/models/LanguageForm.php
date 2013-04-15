<?php

/**
 * ConfirmForm class.
 * ConfirmForm is the data structure for confirming users account operations.
 * It is used by the 'confirm' action of 'transactionController'.
 */
class LanguageForm extends CFormModel
{
	public $language;
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
			array('language', 'safe'),
			array('url', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'laguage'=>Yii::t('app','Language'),
		);
	}
}

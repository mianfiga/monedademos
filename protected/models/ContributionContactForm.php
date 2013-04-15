<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContributionContactForm extends CFormModel
{
	public $subject;
	public $body;
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('subject, body', 'required'),
			// email has to be a valid email address
//			array('email', 'email'),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
            'name' => Yii::t('app','Name'),
            'email' => Yii::t('app','E-mail'),
            'subject' => Yii::t('app','Subject'),
            'body' => Yii::t('app','Body'),
			'verifyCode'=> Yii::t('app','Verification Code'),
		);
	}
}

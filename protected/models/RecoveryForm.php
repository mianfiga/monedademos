<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RecoveryForm extends CFormModel {

    public $username;
    public $verifyCode;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.

     */
    public function rules() {
        return array(
            // username and password are required
            array('username', 'required'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements()),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => Yii::t('app', 'Username or e-mail'),
        );
    }

    /**
     * Recovers user password 1st send e-mail
     * @return boolean whether login is successful
     */
    public function recover() {

        $username = strtolower($this->username);

        $user = User::model()->find('LOWER(username)=?', array($username));

        if ($user === null) {
            $user = User::model()->find('LOWER(email)=?', array($username));
        }

        if ($user === null) {
            $this->addError('username', Yii::t('app', 'Username or e-mail not found.'));
        } else {
            $user->saveAttributes(array('magic' => User::randString(64)));

            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= "From: noreply@monedademos.es\r\n";
            if (mail($user->email, Yii::t('notification', '[DEMOS] Password recovery'), CController::renderInternal(Yii::getPathOfAlias('application.views') . '/recovery/_mail.php', array('user' => $user), true), $headers)) {
                Yii::app()->user->setFlash('success', Yii::t('app', 'Check your E-mail for further instructions'));
                return true;
            } else
                Yii::app()->user->setFlash('error', Yii::t('app', 'E-mail not sent'));
        }
        return false;
    }

}

<?php

/**
 * This is the model class for table "{{authorization}}".
 *
 * The followings are the available columns in table '{{authorization}}':
 * @property string $user_id
 * @property string $account_id
 * @property string $code
 * @property string $class
 * @property string $title
 * @property string $salt
 * @property string $password
 * @property integer $wrong_pass_count
 * @property string $added
 * @property string $blocked
 * @property string $deleted
 */
class AuthorizationBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AuthorizationBase the static model class
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
		return '{{authorization}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, account_id, code, salt, password', 'required'),
			array('wrong_pass_count', 'numerical', 'integerOnly'=>true),
			array('user_id, account_id, class', 'length', 'max'=>10),
			array('code', 'length', 'max'=>1),
			array('title', 'length', 'max'=>127),
			array('salt, password', 'length', 'max'=>128),
			array('added, blocked, deleted', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, account_id, code, class, title, salt, password, wrong_pass_count, added, blocked, deleted', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'account_id' => 'Account',
			'code' => 'Code',
			'class' => 'Class',
			'title' => 'Title',
			'salt' => 'Salt',
			'password' => 'Password',
			'wrong_pass_count' => 'Wrong Pass Count',
			'added' => 'Added',
			'blocked' => 'Blocked',
			'deleted' => 'Deleted',
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

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('class',$this->class,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('wrong_pass_count',$this->wrong_pass_count);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('blocked',$this->blocked,true);
		$criteria->compare('deleted',$this->deleted,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
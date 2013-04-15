<?php

/**
 * This is the model class for table "{{account}}".
 *
 * The followings are the available columns in table '{{account}}':
 * @property string $id
 * @property string $class
 * @property string $credit
 * @property string $earned
 * @property string $spended
 * @property string $title
 * @property string $access
 * @property string $added
 * @property string $last_action
 * @property string $blocked
 * @property string $deleted
 *
 * The followings are the available model relations:
 * @property User[] $rbuUsers
 * @property Pending[] $pendings
 * @property Pending[] $pendings1
 * @property Transaction[] $transactions
 * @property Transaction[] $transactions1
 */
class AccountBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AccountBase the static model class
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
		return '{{account}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('class', 'length', 'max'=>6),
			array('credit, earned, spended', 'length', 'max'=>20),
			array('title', 'length', 'max'=>127),
			array('access', 'length', 'max'=>7),
			array('added, last_action, blocked, deleted', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, class, credit, earned, spended, title, access, added, last_action, blocked, deleted', 'safe', 'on'=>'search'),
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
			'rbuUsers' => array(self::MANY_MANY, 'User', '{{authorization}}(account_id, user_id)'),
			'pendings' => array(self::HAS_MANY, 'Pending', 'charge_account'),
			'pendings1' => array(self::HAS_MANY, 'Pending', 'deposit_account'),
			'transactions' => array(self::HAS_MANY, 'Transaction', 'charge_account'),
			'transactions1' => array(self::HAS_MANY, 'Transaction', 'deposit_account'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'class' => 'Class',
			'credit' => 'Credit',
			'earned' => 'Earned',
			'spended' => 'Spended',
			'title' => 'Title',
			'access' => 'Access',
			'added' => 'Added',
			'last_action' => 'Last Action',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('class',$this->class,true);
		$criteria->compare('credit',$this->credit,true);
		$criteria->compare('earned',$this->earned,true);
		$criteria->compare('spended',$this->spended,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('access',$this->access,true);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('last_action',$this->last_action,true);
		$criteria->compare('blocked',$this->blocked,true);
		$criteria->compare('deleted',$this->deleted,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
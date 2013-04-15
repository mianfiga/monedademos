<?php

/**
 * This is the model class for table "{{market_joined}}".
 *
 * The followings are the available columns in table '{{market_joined}}':
 * @property string $ad_id
 * @property string $user_id
 * @property string $comment
 * @property integer $show_mail
 * @property string $status
 * @property string $added
 * @property string $updated
 */
class MarketJoinedBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MarketJoinedBase the static model class
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
		return '{{market_joined}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ad_id, user_id, status', 'required'),
			array('show_mail', 'numerical', 'integerOnly'=>true),
			array('ad_id', 'length', 'max'=>20),
			array('user_id, status', 'length', 'max'=>10),
			array('comment, added, updated', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ad_id, user_id, comment, show_mail, status, added, updated', 'safe', 'on'=>'search'),
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
			'ad_id' => 'Ad',
			'user_id' => 'User',
			'comment' => 'Comment',
			'show_mail' => 'Show Mail',
			'status' => 'Status',
			'added' => 'Added',
			'updated' => 'Updated',
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

		$criteria->compare('ad_id',$this->ad_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('show_mail',$this->show_mail);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
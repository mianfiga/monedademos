<?php

/**
 * This is the model class for table "{{tribe_balance}}".
 *
 * The followings are the available columns in table '{{tribe_balance}}':
 * @property string $from_id
 * @property string $to_id
 * @property string $period_amount
 * @property string $total_amount
 *
 * The followings are the available model relations:
 * @property Tribe $from
 * @property Tribe $to
 */
class TribeBalance extends TribeBalanceBase
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TribeBalanceBase the static model class
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
		return '{{tribe_balance}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('from_id, to_id, period_amount, total_amount', 'required'),
			array('from_id, to_id', 'length', 'max'=>11),
			array('period_amount, total_amount', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('from_id, to_id, period_amount, total_amount', 'safe', 'on'=>'search'),
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
			'from' => array(self::BELONGS_TO, 'Tribe', 'from_id'),
			'to' => array(self::BELONGS_TO, 'Tribe', 'to_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'from_id' => 'From',
			'to_id' => 'To',
			'period_amount' => 'Period Amount',
			'total_amount' => 'Total Amount',
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

		$criteria->compare('from_id',$this->from_id,true);
		$criteria->compare('to_id',$this->to_id,true);
		$criteria->compare('period_amount',$this->period_amount,true);
		$criteria->compare('total_amount',$this->total_amount,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
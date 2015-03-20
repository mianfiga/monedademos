<?php

/**
 * This is the model class for table "{{period}}".
 *
 * The followings are the available columns in table '{{period}}':
 * @property string $id
 * @property string $tribe_id
 * @property string $added
 * @property string $movements
 * @property string $active_users
 *
 * The followings are the available model relations:
 * @property Tribe $tribe
 */
class PeriodBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PeriodBase the static model class
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
		return '{{period}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('added', 'required'),
			array('tribe_id', 'length', 'max'=>11),
			array('movements, active_users', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tribe_id, added, movements, active_users', 'safe', 'on'=>'search'),
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
			'tribe' => array(self::BELONGS_TO, 'Tribe', 'tribe_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tribe_id' => 'Tribe',
			'added' => 'Added',
			'movements' => 'Movements',
			'active_users' => 'Active Users',
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
		$criteria->compare('tribe_id',$this->tribe_id,true);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('movements',$this->movements,true);
		$criteria->compare('active_users',$this->active_users,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
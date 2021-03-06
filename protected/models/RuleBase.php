<?php

/**
 * This is the model class for table "{{rule}}".
 *
 * The followings are the available columns in table '{{rule}}':
 * @property string $id
 * @property string $tribe_id
 * @property string $added
 * @property string $salary
 * @property string $min_salary
 * @property integer $multiplier
 * @property integer $system_adapted
 *
 * The followings are the available model relations:
 * @property Tribe $tribe
 */
class RuleBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RuleBase the static model class
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
		return '{{rule}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('salary, min_salary, multiplier', 'required'),
			array('multiplier, system_adapted', 'numerical', 'integerOnly'=>true),
			array('tribe_id', 'length', 'max'=>11),
			array('salary, min_salary', 'length', 'max'=>20),
			array('added', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tribe_id, added, salary, min_salary, multiplier, system_adapted', 'safe', 'on'=>'search'),
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
			'salary' => 'Salary',
			'min_salary' => 'Min Salary',
			'multiplier' => 'Multiplier',
			'system_adapted' => 'System Adapted',
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
		$criteria->compare('salary',$this->salary,true);
		$criteria->compare('min_salary',$this->min_salary,true);
		$criteria->compare('multiplier',$this->multiplier);
		$criteria->compare('system_adapted',$this->system_adapted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
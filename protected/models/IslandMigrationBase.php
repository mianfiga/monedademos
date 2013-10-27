<?php

/**
 * This is the model class for table "{{island_migration}}".
 *
 * The followings are the available columns in table '{{island_migration}}':
 * @property string $id
 * @property string $entity_id
 * @property string $to_id
 * @property string $added
 * @property string $executed_at
 *
 * The followings are the available model relations:
 * @property Entity $entity
 * @property Island $to
 */
class IslandMigrationBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IslandMigrationBase the static model class
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
		return '{{island_migration}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('entity_id, to_id', 'required'),
			array('entity_id, to_id', 'length', 'max'=>11),
			array('added, executed_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, entity_id, to_id, added, executed_at', 'safe', 'on'=>'search'),
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
			'entity' => array(self::BELONGS_TO, 'Entity', 'entity_id'),
			'to' => array(self::BELONGS_TO, 'Island', 'to_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'entity_id' => 'Entity',
			'to_id' => 'To',
			'added' => 'Added',
			'executed_at' => 'Executed At',
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
		$criteria->compare('entity_id',$this->entity_id,true);
		$criteria->compare('to_id',$this->to_id,true);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('executed_at',$this->executed_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
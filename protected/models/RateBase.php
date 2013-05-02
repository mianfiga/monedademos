<?php

/**
 * This is the model class for table "{{rate}}".
 *
 * The followings are the available columns in table '{{rate}}':
 * @property string $id
 * @property string $to_id
 * @property string $from_id
 * @property string $sid
 * @property string $type
 * @property integer $puntuation
 * @property string $comment
 * @property string $added
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property Entity $to
 * @property Entity $from
 */
class RateBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RateBase the static model class
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
		return '{{rate}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('to_id, from_id', 'required'),
			array('puntuation', 'numerical', 'integerOnly'=>true),
			array('to_id, from_id', 'length', 'max'=>11),
			array('sid', 'length', 'max'=>127),
			array('type', 'length', 'max'=>7),
			array('comment, added, updated', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, to_id, from_id, sid, type, puntuation, comment, added, updated', 'safe', 'on'=>'search'),
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
			'to' => array(self::BELONGS_TO, 'Entity', 'to_id'),
			'from' => array(self::BELONGS_TO, 'Entity', 'from_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'to_id' => 'To',
			'from_id' => 'From',
			'sid' => 'Sid',
			'type' => 'Type',
			'puntuation' => 'Puntuation',
			'comment' => 'Comment',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('to_id',$this->to_id,true);
		$criteria->compare('from_id',$this->from_id,true);
		$criteria->compare('sid',$this->sid,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('puntuation',$this->puntuation);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
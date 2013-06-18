<?php

/**
 * This is the model class for table "{{brand}}".
 *
 * The followings are the available columns in table '{{brand}}':
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $summary
 * @property string $description
 * @property string $image
 * @property string $created_by
 * @property string $added
 * @property string $updated
 * @property string $deleted
 *
 * The followings are the available model relations:
 * @property Entity $createdBy
 */
class BrandBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BrandBase the static model class
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
		return '{{brand}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email', 'required'),
			array('name', 'length', 'max'=>127),
			array('email', 'length', 'max'=>255),
			array('image', 'length', 'max'=>254),
			array('created_by', 'length', 'max'=>10),
			array('summary, description, added, updated, deleted', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, email, summary, description, image, created_by, added, updated, deleted', 'safe', 'on'=>'search'),
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
			'createdBy' => array(self::BELONGS_TO, 'Entity', 'created_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'email' => 'Email',
			'summary' => 'Summary',
			'description' => 'Description',
			'image' => 'Image',
			'created_by' => 'Created By',
			'added' => 'Added',
			'updated' => 'Updated',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('created_by',$this->created_by,true);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('deleted',$this->deleted,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
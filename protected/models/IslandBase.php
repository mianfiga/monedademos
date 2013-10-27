<?php

/**
 * This is the model class for table "{{island}}".
 *
 * The followings are the available columns in table '{{island}}':
 * @property string $id
 * @property string $nickname
 * @property string $name
 * @property string $email
 * @property string $summary
 * @property string $description
 * @property string $image
 * @property string $last_action
 * @property string $group_id
 * @property string $created_by
 * @property string $added
 * @property string $updated
 * @property string $deleted
 *
 * The followings are the available model relations:
 * @property Entity[] $entities
 * @property Entity $createdBy
 * @property IslandGroup $group
 * @property IslandBalance[] $islandBalances
 * @property IslandBalance[] $islandBalances1
 * @property IslandMigration[] $islandMigrations
 * @property Period[] $periods
 * @property Record[] $records
 * @property Rule[] $rules
 */
class IslandBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IslandBase the static model class
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
		return '{{island}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nickname, email', 'required'),
			array('nickname, name', 'length', 'max'=>127),
			array('email', 'length', 'max'=>255),
			array('image', 'length', 'max'=>254),
			array('group_id', 'length', 'max'=>11),
			array('created_by', 'length', 'max'=>10),
			array('summary, description, last_action, added, updated, deleted', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nickname, name, email, summary, description, image, last_action, group_id, created_by, added, updated, deleted', 'safe', 'on'=>'search'),
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
			'entities' => array(self::HAS_MANY, 'Entity', 'island_id'),
			'createdBy' => array(self::BELONGS_TO, 'Entity', 'created_by'),
			'group' => array(self::BELONGS_TO, 'IslandGroup', 'group_id'),
			'islandBalances' => array(self::HAS_MANY, 'IslandBalance', 'from_id'),
			'islandBalances1' => array(self::HAS_MANY, 'IslandBalance', 'to_id'),
			'islandMigrations' => array(self::HAS_MANY, 'IslandMigration', 'to_id'),
			'periods' => array(self::HAS_MANY, 'Period', 'island_id'),
			'records' => array(self::HAS_MANY, 'Record', 'island_id'),
			'rules' => array(self::HAS_MANY, 'Rule', 'island_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nickname' => 'Nickname',
			'name' => 'Name',
			'email' => 'Email',
			'summary' => 'Summary',
			'description' => 'Description',
			'image' => 'Image',
			'last_action' => 'Last Action',
			'group_id' => 'Group',
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
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('last_action',$this->last_action,true);
		$criteria->compare('group_id',$this->group_id,true);
		$criteria->compare('created_by',$this->created_by,true);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('deleted',$this->deleted,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
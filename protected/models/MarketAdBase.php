<?php

/**
 * This is the model class for table "{{market_ad}}".
 *
 * The followings are the available columns in table '{{market_ad}}':
 * @property string $id
 * @property string $title
 * @property string $class
 * @property string $type
 * @property string $summary
 * @property string $price
 * @property string $description
 * @property string $image
 * @property string $mailmode
 * @property integer $visible
 * @property string $expiration
 * @property string $created_by
 * @property string $added
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property User $createdBy
 * @property User[] $rbuUsers
 */
class MarketAdBase extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MarketAdBase the static model class
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
		return '{{market_ad}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('class, type, price, mailmode, expiration', 'required'),
			array('visible', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>127),
			array('class', 'length', 'max'=>7),
			array('type', 'length', 'max'=>11),
			array('price', 'length', 'max'=>20),
			array('image', 'length', 'max'=>254),
			array('mailmode', 'length', 'max'=>9),
			array('created_by', 'length', 'max'=>10),
			array('summary, description, added, updated', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, class, type, summary, price, description, image, mailmode, visible, expiration, created_by, added, updated', 'safe', 'on'=>'search'),
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
			'createdBy' => array(self::BELONGS_TO, 'User', 'created_by'),
			'rbuUsers' => array(self::MANY_MANY, 'User', '{{market_joined}}(ad_id, user_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'class' => 'Class',
			'type' => 'Type',
			'summary' => 'Summary',
			'price' => 'Price',
			'description' => 'Description',
			'image' => 'Image',
			'mailmode' => 'Mailmode',
			'visible' => 'Visible',
			'expiration' => 'Expiration',
			'created_by' => 'Created By',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('class',$this->class,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('mailmode',$this->mailmode,true);
		$criteria->compare('visible',$this->visible);
		$criteria->compare('expiration',$this->expiration,true);
		$criteria->compare('created_by',$this->created_by,true);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
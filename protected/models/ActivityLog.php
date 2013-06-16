<?php

/**
 * This is the model class for table "{{activity_log}}".
 *
 * The followings are the available columns in table '{{activity_log}}':
 * @property string $id
 * @property string $entity_id
 * @property string $action
 * @property string $related_sid
 * @property string $ip
 * @property string $added
 *
 * The followings are the available model relations:
 * @property Entity $entity
 */
class ActivityLog extends ActivityLogBase
{
    
    const LOGIN = 'login';
    const LOGOUT = 'logout';
    const TRANSACTION = 'transaction';

    /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ActivityLogBase the static model class
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
		return '{{activity_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('action, ip', 'required'),
			array('entity_id', 'length', 'max'=>11),
			array('action, related_sid', 'length', 'max'=>127),
			array('ip', 'length', 'max'=>41),
			array('added', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, entity_id, action, related_sid, ip, added', 'safe', 'on'=>'search'),
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
			'action' => 'Action',
			'related_sid' => 'Related Sid',
			'ip' => 'Ip',
			'added' => 'Added',
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
		$criteria->compare('action',$this->action,true);
		$criteria->compare('related_sid',$this->related_sid,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('added',$this->added,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    static public function add ($entity_id, $action, $related_sid=null){
        
        $request = Yii::app()->getRequest();
        
        $act = new ActivityLog;
        $act->entity_id = $entity_id;
        $act->action = $action;
        $act->related_sid = $related_sid;
        $act->ip = $request->getUserHostAddress();
        $act->added = Common::datetime();
        
        $act->save();
    }
}

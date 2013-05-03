<?php

/**
 * This is the model class for table "{{entity}}".
 *
 * The followings are the available columns in table '{{entity}}':
 * @property string $id
 * @property string $class
 * @property integer $object_id
 */
class Entity extends EntityBase {

    public $rate;
    protected $_object;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return EntityBase the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{entity}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('class, object_id', 'required'),
            array('object_id', 'numerical', 'integerOnly' => true),
            array('class', 'length', 'max' => 127),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, class, object_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'marketAdCreator' => array(self::HAS_MANY, 'MarketAd', 'created_by'),
            'marketAds' => array(self::MANY_MANY, 'MarketAd', '{{market_joined}}(entity_id, ad_id)'),
            'marketJoined' => array(self::HAS_MANY, 'MarketJoined', 'entity_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'class' => 'Class',
            'object_id' => 'Object',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('class', $this->class, true);
        $criteria->compare('object_id', $this->object_id);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function get($object) {
        return self::model()->findByAttributes(array(
                    'class' => get_class($object),
                    'object_id' => $object->id));
    }

    public function getObject() {
        if ($this->_object == null) {
            $classname = $this->class;
            return $this->_object = $classname::model()->findByPk($this->object_id);
        }

        return $this->_object;
    }

    public function getName() {
        return $this->getObject()->name;
    }

    public function getSurname() {
        return $this->getObject()->surname;
    }

    public function getEmail() {
        return $this->getObject()->email;
    }

    public function getCulture() {
        return $this->getObject()->culture;
    }

    protected function afterFind() {
        parent::afterFind();
        if($this->rates == 0){
            $this->rate = Rate::DEFAULT_VALUE;
        }else{
            $this->rate = round($this->points / $this->rates);
        }
            
        
    }

}
<?php

/**
 * This is the model class for table "{{rate}}".
 *
 * The followings are the available columns in table '{{rate}}':
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
class Rate extends RateBase {

    const DEFAULT_VALUE = 3;
    const TYPE_NEUTRAL = 'neutral';
    const TYPE_CLIENT = 'client';
    const TYPE_VENDOR = 'vendor';

    public $url;
    private $_object;
    private $_isNew;
    private $_puntuation;

    /* private $_isValid;

      public function __construct($sid = null) {
      parent::__construct();

      if($sid != null){
      $this->_isValid = $this->fill($sid);
      }
      } */

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return RateBase the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{rate}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('puntuation', 'required'),
            array('puntuation', 'numerical', 'integerOnly' => true),
            //array('to_id, from_id', 'length', 'max' => 11),
            array('sid', 'length', 'max' => 127),
            //array('type', 'length', 'max' => 7),
            array('comment, url', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('to_id, from_id, sid, type, puntuation, comment, added, updated', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
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
    public function attributeLabels() {
        return array(
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
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('to_id', $this->to_id, true);
        $criteria->compare('from_id', $this->from_id, true);
        $criteria->compare('sid', $this->sid, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('puntuation', $this->puntuation);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('added', $this->added, true);
        $criteria->compare('updated', $this->updated, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function alreadyExists() {
        $found = self::model()->findByPk(array(
        'to_id' => $this->to_id,
        'from_id' => $this->from_id,
        'sid' => $this->sid));
        
        if ($found) {
            $this->setIsNewRecord(false);
            $this->_puntuation = $this->puntuation = $found->puntuation;
            $this->comment = $found->comment;
            $this->updated = $found->updated;
            $this->added = $found->added;
            return true;
        }
        return false;
    }

    public function getObject() {
        if ($this->_object == null) {
            $this->_object = Sid::getObject($this->sid);
        }
        return $this->_object;
    }

    public function fillPartial() {
        $data = explode('-', $this->sid);
        switch ($data[0]) {
            case 'tr':
                if($this->object->charge_entity == $this->object->deposit_entity){
                    return false;
                }

                if ($this->object->charge_entity == $this->from_id) {
                    $this->type = self::TYPE_VENDOR;
                    $this->to_id = $this->object->deposit_entity;
                } else if ($this->object->deposit_entity == $this->from_id) {
                    $this->type = self::TYPE_CLIENT;
                    $this->to_id = $this->object->charge_entity;
                } else {
                    return false;
                }
        }
        return true;
    }

    public function fill($sid = null) {
        if ($sid != null) {
            $this->sid = $sid;
        }

        if ($this->object == null) {
            return false;
        }

        if (($this->from_id = Yii::app()->user->getId()) == null) {
            return false;
        }

        return $this->fillPartial();
    }

    protected function afterFind() {
        parent::afterFind();
// guardamos la valoración existente, en caso de que cambio debemos actualizar la entidad 
        $this->_puntuation = $this->puntuation;
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->_isNew = true;
                $this->added = date('YmdHis');
            }
            $this->updated = date('YmdHis');
            return $this->fill();
        }
    }

    protected function afterSave() {
        parent::afterSave();
        if ($this->_isNew) {
            $this->to->saveAttributes(array(
                'points' => $this->to->points + $this->puntuation,
                'rates' => $this->to->rates + 1
            ));
            
        } else {
            $this->to->saveAttributes(array(
                'points' => $this->to->points + $this->puntuation - $this->_puntuation,
            ));
        }
    }

}

<?php

/**
 * This is the model class for table "{{market_ad_tribe}}".
 *
 * The followings are the available columns in table '{{market_ad_tribe}}':
 * @property string $ad_id
 * @property string $tribe_id
 * @property string $added
 * @property string $updated
 * @property string $deleted
 */
class MarketAdTribe extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MarketAdTribe the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{market_ad_tribe}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ad_id, tribe_id', 'required'),
            array('ad_id', 'length', 'max' => 20),
            array('tribe_id', 'length', 'max' => 11),
            array('added, updated, deleted', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('ad_id, tribe_id, added, updated, deleted', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'ad_id' => 'Id Ad',
            'tribe_id' => 'Id Tribe',
            'added' => 'Added',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
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

        $criteria->compare('ad_id', $this->ad_id, true);
        $criteria->compare('tribe_id', $this->tribe_id, true);
        $criteria->compare('added', $this->added, true);
        $criteria->compare('updated', $this->updated, true);
        $criteria->compare('deleted', $this->deleted, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    protected function beforeSave() {
        if (!parent::beforeSave()) {
            return false;
        }

        if ($this->isNewRecord) {
            $this->added = Common::datetime();
        }
        return true;
    }

}

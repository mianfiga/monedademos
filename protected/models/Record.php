<?php

/**
 * This is the model class for table "{{record}}".
 *
 * The followings are the available columns in table '{{record}}':
 * @property string $id
 * @property string $added
 * @property string $total_amount
 * @property string $user_count
 * @property string $account_count
 */
class Record extends RecordBase {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return RecordBase the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{record}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
        return array(
            array('added, total_amount, user_count, account_count', 'required'),
            array('total_amount, user_count', 'length', 'max' => 20),
            array('account_count', 'length', 'max' => 10),
            // The following rule is used by search().
// Please remove those attributes that should not be searched.
            array('id, added, total_amount, user_count, account_count', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'added' => 'Added',
            'total_amount' => 'Total Amount',
            'user_count' => 'User Count',
            'account_count' => 'Account Count',
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
        $criteria->compare('added', $this->added, true);
        $criteria->compare('total_amount', $this->total_amount, true);
        $criteria->compare('user_count', $this->user_count, true);
        $criteria->compare('account_count', $this->account_count, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getLastRecord($tribe_id = Tribe::DEFAULT_TRIBE) {
        return self::model()->find("tribe_id='" . $tribe_id . "' ORDER BY id DESC");
    }

    public static function updateRecord($array, $tribe_id = null) {
        $record = self::getLastRecord($tribe_id);
        $date = Common::date();
        if(!$record){
            $record = new Record;
            $record->added = $date;
            $record->total_amount = 0;
            $record->user_count = 0;
            $record->account_count = 0;
        }

        if (isset($array['total_amount'])){
            $record->total_amount = $array['total_amount'];
        }
        if (isset($array['user_count'])){
            $record->user_count = $array['user_count'];
        }
        if (isset($array['account_count'])){
            $record->account_count = $array['account_count'];
        }
        if ($date != $record->added) {
            $record->added = $date;
            $record->setIsNewRecord(true);
            $record->id = null;
        }
        $record->tribe_id = $tribe_id;
        if(!$record->save()){
          print_r($record->attributes);
          throw new Exception('Record::UpdateRecord failed');
        };
    }

}

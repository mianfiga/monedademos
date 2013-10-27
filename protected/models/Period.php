<?php

/**
 * This is the model class for table "{{period}}".
 *
 * The followings are the available columns in table '{{period}}':
 * @property string $id
 * @property string $added
 * @property string $movements
 * @property string $active_users
 */
class Period extends PeriodBase {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PeriodBase the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{period}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('movements, active_users', 'length', 'max' => 10),
                // The following rule is used by search().
                // Please remove those attributes that should not be searched.
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
            'id' => 'ID',
            'added' => 'Added',
            'movements' => 'Movements',
            'active_users' => 'Active Users',
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
        $criteria->compare('movements', $this->movements, true);
        $criteria->compare('active_users', $this->active_users, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * @return Period The last period registered.
     */
    public static function getLast($island_id = 1) {
        return self::model()->find('island_id = \'' . $island_id . '\' ORDER BY added DESC');
    }

    /**
     * @return Period The pre-last period registered.
     */
    public static function getPrevious($island_id = 1) {
        $periods = self::model()->findAll('island_id = \'' . $island_id . '\' ORDER BY id DESC');
        return next($periods);
    }

    /**
     * @return String The date when the last period was registered.
     */
    public static function getLastDate($island_id = 1) {
        $period = self::getLast($island_id);
        return $period->added;
    }

    public static function calculate($island_id = 1, $save = false) {
        $period = new Period;
        $oldPeriodDate = self::getLastDate();


        $period->active_users = Entity::model()->with(
                        'chargeTransactions', 'depositTransactions')->count(
                't.class=\'User\' AND t.island_id = \'' . $island_id . '\'
                   (   (chargeTransactions.executed_at > \'' . $oldPeriodDate . '\' AND 
                        chargeTransactions.class in (\'' . Transaction::CLASS_TRANSFER . '\', \'' . Transaction::CLASS_CHARGE . '\'))
                    OR (depositTransactions.executed_at > \'' . $oldPeriodDate . '\' AND
                        depositTransactions.class in (\'' . Transaction::CLASS_TRANSFER . '\', \'' . Transaction::CLASS_CHARGE . '\')))');

        $sum = Account::model()->findBySql('select sum(`earned`) as `earned` ' .
                'from `' . Account::model()->tableSchema->name . '` as account, `' . Authorization::model()->tableSchema->name . '` as auth, `' . Entity::model()->tableSchema->name . '` as entity' .
                ' where entity.island_id=\'' . $island_id . '\'' .
                '   AND entity.id = auth.entity_id ' .
                '   AND auth.account_id = account.id' .
                '   AND auth.class =\'' . Authorization::CLASS_HOLDER . '\'', array());
        $period->movements = $sum->earned;
        $period->added = date('Y-m-d');
        if ($save) {
            $period->save();
        }
        return $period;
    }

}

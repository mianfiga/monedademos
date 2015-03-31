<?php

/**
 * This is the model class for table "{{rule}}".
 *
 * The followings are the available columns in table '{{rule}}':
 * @property string $id
 * @property string $added
 * @property string $salary
 * @property integer $multiplier
 */
class Rule extends RuleBase {

    const MIN_SALARY_DIVIDER_LOW = 2.5;
    const MIN_SALARY_DIVIDER_HIGH = 5;
    const SALARY_HIGH = 10;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return RuleBase the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('salary, multiplier', 'required'),
            array('multiplier', 'numerical', 'integerOnly' => true),
            array('salary', 'length', 'max' => 20),
            array('added', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, added, salary, multiplier', 'safe', 'on' => 'search'),
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
            'salary' => 'Salary',
            'multiplier' => 'Multiplier',
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
        $criteria->compare('salary', $this->salary, true);
        $criteria->compare('multiplier', $this->multiplier);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * @return float the percent (act. perone) corresponding to the tax to apply
     */
    public function getTaxProportion() {
        return 1.0 / $this->multiplier;
    }

    public static function getTomorrowRule($tribe_group_id = Tribe::DEFAULT_TRIBE_GROUP) {
        return self::model()->find('tribe_group_id = \'' . $tribe_group_id . '\' AND added <= \'' . date(Common::DATETIME_FORMAT, strtotime('tomorrow')) . '\' ORDER BY id DESC');
    }

    public static function getCurrentRule($tribe_group_id = Tribe::DEFAULT_TRIBE_GROUP) {
        return self::model()->find('tribe_group_id = \'' . $tribe_group_id . '\' AND added <= \'' . date(Common::DATETIME_FORMAT) . '\' ORDER BY id DESC');
    }

    public static function getAdaptedRule($tribe_group_id = Tribe::DEFAULT_TRIBE_GROUP) {
        return self::model()->find('tribe_group_id = \'' . $tribe_group_id . '\' AND system_adapted=1 ORDER BY id DESC');
    }

    public static function getPreviousRule($tribe_group_id = Tribe::DEFAULT_TRIBE_GROUP) {
        $rules = self::model()->findAll('tribe_group_id = \'' . $tribe_group_id . '\' AND added <= \'' . date(Common::DATETIME_FORMAT) . '\' ORDER BY id DESC');
        return $rules->next();
    }

    public static function getDateRule($date, $tribe_group_id = Tribe::DEFAULT_TRIBE_GROUP) {
        return self::model()->find('tribe_group_id = \'' . $tribe_group_id . '\' AND added <= \'' . $date . '\' ORDER BY id DESC');
    }

    /*public static function addPeriodRule($period = null) {
        if ($period === null) {
            $period = Period::getLast();
        }

        if ($period->active_users == 0)
            return;

        $newRule = new Rule;
        $curRule = self::getCurrentRule();

        $newRule->multiplier = $curRule->multiplier;
        $newRule->salary = $period->movements / $period->active_users;
        if ($newRule->salary < Transaction::amountUserToSystem(Rule::SALARY_HIGH)) {
            $newRule->min_salary = $newRule->salary / Rule::MIN_SALARY_DIVIDER_LOW;
        } else {
            $newRule->min_salary = $newRule->salary / Rule::MIN_SALARY_DIVIDER_HIGH;
        }
        $newRule->added = date('Y-m-d H:i:s', mktime(0, 0, 0, date("n") + 1));
        $newRule->system_adapted = 0;

        $newRule->save();
    }*/

    public static function addTribeGroupRule($tribe_group_id) {
        $tribes = Tribe::model()->findAllByAttributes(array('group_id' => $tribe_group_id));

        $movements = 0;
        $active_users = 0;
        foreach ($tribes as $tribe) {
            $period = Period::getLast($tribe->id);
            if (!$period || $period->active_users == 0){
                continue;
            }
                
            $movements += $period->movements;
            $active_users +=$period->active_users;
        }
        if ($active_users == 0){
            return;
        }
            

        $new_rule = new Rule;
        $curRule = self::getCurrentRule($tribe_group_id);
        
        $new_rule->tribe_group_id = $tribe_group_id;
        $new_rule->multiplier = $curRule->multiplier;
        $new_rule->salary = $movements / $active_users;
        if ($new_rule->salary < Transaction::amountUserToSystem(Rule::SALARY_HIGH)) {
            $new_rule->min_salary = $new_rule->salary / Rule::MIN_SALARY_DIVIDER_LOW;
        } else {
            $new_rule->min_salary = $new_rule->salary / Rule::MIN_SALARY_DIVIDER_HIGH;
        }
        $new_rule->added = date(Common::DATETIME_FORMAT, mktime(0, 0, 0, date("n") + 1));
        $new_rule->system_adapted = 0;

        $new_rule->save();
    }

}

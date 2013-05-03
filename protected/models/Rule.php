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

    const MIN_SALARY_DIVIDER = 2.5;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RuleBase the static model class
	 */
	public static function model($className=__CLASS__)
	{
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

    public static function getTomorrowRule() {
        return self::model()->find('added <= ' . date(Common::DATETIME_FORMAT, strtotime('tomorrow')) . ' ORDER BY id DESC');
    }

    public static function getCurrentRule() {
        return self::model()->find('added <= \'' . date(Common::DATETIME_FORMAT) . '\' ORDER BY id DESC');
    }

    public static function getAdaptedRule() {
        return self::model()->find('system_adapted=1 ORDER BY id DESC');
    }

    public static function getPreviousRule() {
        $rules = self::model()->findAll('added <= \'' . date(Common::DATETIME_FORMAT) . '\' ORDER BY id DESC');
        return $rules->next();
    }

    public static function getDateRule($date) {
        return self::model()->find('added <= \'' . $date . '\' ORDER BY id DESC');
    }

    public static function addPeriodRule($period = null) {
        if ($period === null) {
            $period = Period::getLast();
        }

        if ($period->active_users == 0)
            return;

        $newRule = new Rule;
        $curRule = self::getCurrentRule();

        $newRule->multiplier = $curRule->multiplier;
        $newRule->salary = $period->movements / $period->active_users;
        $newRule->min_salary = $newRule->salary / Rule::MIN_SALARY_DIVIDER;
        $newRule->added = date('Y-m-d H:i:s', mktime (0, 0, 0, date("n")+1));
        $newRule->system_adapted = 0;

        $newRule->save();
    }

}
<?php

/**
 * This is the model class for table "{{account}}".
 *
 * The followings are the available columns in table '{{account}}':
 * @property string $id
 * @property string $class
 * @property string $credit
 * @property string $earned
 * @property string $spended
 * @property string $title
 * @property string $access
 * @property string $added
 * @property string $last_action
 * @property string $blocked
 * @property string $deleted
 *
 * The followings are the available model relations:
 * @property User[] $rbuUsers
 * @property Pending[] $pendings
 * @property Pending[] $pendings1
 * @property Transaction[] $transactions
 * @property Transaction[] $transactions1
 */
class Account extends AccountBase {

    const ERROR_NOFUNDS = 1;
    const ERROR_WRONG = 2;
    const ERROR_BLOCKED = 4;
    const ERROR_DELETED = 8;
    const CLASS_FUND = 'fund';    //1;
    const CLASS_SYSTEM = 'system';  //2;
    const CLASS_USER = 'user';    //3;
    const CLASS_GROUP = 'group';   //4;
    const FUND_ACCOUNT = 1;
    const FUND_ENTITY = 1;

    protected $_isNew = false;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserBase the static model class
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
//			array('credit, earned, spended', 'numerical', 'integerOnly'=>true),
            array('class', 'length', 'max' => 6),
            array('title', 'length', 'max' => 127),
            array('access', 'length', 'max' => 7),
            array('added, blocked, deleted', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, class, credit, earned, spended, title, access, added, blocked, deleted', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'holders' => array(
                self::MANY_MANY,
                'Entity',
                '{{authorization}}(account_id, entity_id)',
                'on' => '`holders_holders`.`class`=\'' . Authorization::CLASS_HOLDER . '\''
            ),
            'entities' => array(
                self::MANY_MANY,
                'Entity',
                '{{authorization}}(account_id, entity_id)',
            ),
            'chargeTransactions' => array(
                self::HAS_MANY,
                'Transaction',
                'charge_account',
            ),
            'depositTransactions' => array(
                self::HAS_MANY,
                'Transaction',
                'deposit_account',
            ),
            'lastSalary' => array(
                self::HAS_ONE,
                'Transaction',
                'deposit_account',
                'condition' => '`class` = \'' . Transaction::CLASS_SALARY . '\'',
                'order' => 'executed_at DESC',
            ),
            'firstSalary' => array(
                self::HAS_ONE,
                'Transaction',
                'deposit_account',
                'condition' => '`class` = \'' . Transaction::CLASS_SALARY . '\'',
                'order' => 'executed_at ASC',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('app', 'ID'),
            'class' => Yii::t('app', 'Class'),
            'credit' => Yii::t('app', 'Credit'),
            'earned' => Yii::t('app', 'Earned'),
            'spended' => Yii::t('app', 'Spended'),
            'title' => Yii::t('app', 'Title'),
            'access' => Yii::t('app', 'Access'),
            'added' => Yii::t('app', 'Added'),
            'blocked' => Yii::t('app', 'Blocked'),
            'deleted' => Yii::t('app', 'Deleted'),
        );
    }

    /**
     * Registers a transaction to subtract taxes of the account
     * @param Rule $rule rule to follow to subtract the taxes.
     */
    public function subTaxes(Rule $rule = null) {
        if ($rule == null) {
            $rule = Rule::getCurrentRule();
        }

        $percent = $rule->getTaxProportion();
        $dateLastPeriod = Period::getLastDate();

        $transaction = new Transaction;

        if ($dateLastPeriod > $this->last_action) {
            //inactive users
            if ($this->credit <= $rule->min_salary) {
                $tax = 0;
                $transaction->subject = Yii::t('app', 'Cooperation taxes ({taxes}% of {credit})', array('{taxes}' => number_format($percent * 100, 1),
                            '{credit}' => Transaction::amountSystemToUser($this->credit)));
            } else if ($this->credit * (1 - $percent) <= $rule->min_salary) {
                $tax = $this->credit - $rule->min_salary;
                $transaction->subject = Yii::t('app', 'Cooperation taxes ({taxes}% of {credit})', array('{taxes}' => number_format($percent * 100, 1),
                            '{credit}' => Transaction::amountSystemToUser($this->credit)));
            } else {
                $tax = $this->credit * $percent;
                $transaction->subject = Yii::t('app', 'Cooperation taxes ({taxes}% of {credit})', array('{taxes}' => number_format($percent * 100, 1),
                            '{credit}' => Transaction::amountSystemToUser($this->credit)));
            }
        } else {
            $tax = $this->credit * $percent;
            $transaction->subject = Yii::t('app', 'Cooperation taxes ({taxes}% of {credit})', array('{taxes}' => number_format($percent * 100, 1),
                        '{credit}' => Transaction::amountSystemToUser($this->credit)));
        }

        //Transaction registration
        $transaction->amount = $tax;
        $transaction->class = Transaction::CLASS_TAX;
        $transaction->deposit_account = self::FUND_ACCOUNT;
        $transaction->charge_account = $this->id;
        $transaction->charge_entity = $this->getHolder()->id;
        $transaction->deposit_entity = self::FUND_ENTITY;

        if (!$transaction->save()) {
//			echo "ERROR";
        }
    }

    /**
     * Registers a transaction to add salary of the account
     * @param $date date corresponding to de salary to register.
     * @param Rule $rule rule to follow to add the salary.
     * @param $related_value If positive: it reffers to the total amount of \
     * profits (positive balance) to calculate the compensation proportions. \
     * If negative: it reffers to the average of loss amount. 
     * @param Rule $rule rule to follow to subtract the taxes.
     * @param $related_value, available amount to compensate.
     * @return Array containing 'salary' the salary assigned and \
     *  'compenzsation' or 'penalization' in each case.
     */
    public function addSalary($date = null, Rule $rule = null, $related_value = 0, $global_amount = 0
    ) {
        if ($date === null) {
            $date = strtotime('today');
        }
        if ($rule === null) {
            $rule = Rule::getCurrentRule();
        }

        // We only pay proportionaly to the current day of month
        //$nummonthdays = date('t', $date) + 0.0;
        //$monthday = date('j', $date);
        //$percent = ($nummonthdays - $monthday + 1) / $nummonthdays;
        //new desition makes this differently
        $percent = 1.0;

        //Adding the salary
        $salary = $rule->salary * $percent;

        $amount = $this->earned - $this->spended - $this->balance + 0.0;

        //If keeps wasting with no earning: no salary
        if ($this->earned == 0 && $this->spended > 0 && $this->balance > 0) {
            $salary = 0;
            $penalty = $rule->salary;
            $ret = array(
                "salary" => $salary,
                "penalty" => $penalty,
            );

            $transaction = new Transaction;

            $transaction->subject = "Sueldo " . Transaction::amountSystemToUser(0) . ' por falta de reciprocidad.';
        } else if ($this->total_earned == 0) { //If have not sell anything yet, just get min salary
            $salary = $rule->min_salary;

            //we still calculate penalties in order to compensate
            if ($amount < 0) {
                $max_penalty = $rule->salary - $rule->min_salary;
                $penalty = ($related_value == 0 ? 0 : min(min(abs($amount / $related_value * $max_penalty), abs($amount)), $max_penalty));
            }else{
                $penalty = 0;
            }
            
            //returning array
            $ret = array(
                "salary" => $salary,
                "penalty" => $penalty,
            );
            $transaction = new Transaction;

            $transaction->subject = "Minimum salary (" . Transaction::amountSystemToUser($rule->min_salary) . ')';

        } else if ($amount < 0) {//If have wasted more than have earned: penalty
            $max_penalty = $rule->salary - $rule->min_salary;
            $penalty = ($related_value == 0 ? 0 : min(min(abs($amount / $related_value * $max_penalty), abs($amount)), $max_penalty));
            $salary -= $penalty;
            //returning array
            $ret = array(
                "salary" => $salary,
                "penalty" => $penalty,
            );

            $transaction = new Transaction;

            $transaction->subject = "Salary (" . Transaction::amountSystemToUser($rule->salary) . ')' . ($penalty > 0 ? ' - Penalty (' .
                            Transaction::amountSystemToUser($penalty) . ')' : '');
        } else { //Don't have wasted more than have earned: compensation
            $compensation = ($related_value == 0 ? 0 : min(abs($amount / $related_value * $global_amount), $amount / 2));
            $salary += $compensation;

            //returning el array
            $ret = array(
                "salary" => $salary,
                "compensation" => $compensation,
            );

            $transaction = new Transaction;
            $transaction->subject = "Salary (" . Transaction::amountSystemToUser($rule->salary) .
                    ($percent < 1 ? " remaining " . number_format($percent * 100, 1) . '% of the month' : '') . ")" .
                    ($compensation > 0 ? " + Compensation (" . Transaction::amountSystemToUser($compensation) . ")" : '');
        }

        //Add salary transaction
        $transaction->amount = round($salary);
        $transaction->class = Transaction::CLASS_SALARY;
        $transaction->deposit_entity = $this->getHolder()->id;
        $transaction->deposit_account = $this->id;
        $transaction->charge_entity = self::FUND_ENTITY;
        $transaction->charge_account = self::FUND_ACCOUNT;


        if (!$transaction->save()) {
//			echo "ERROR";
        }

        return $ret;
    }

    /**
     * Add Salary to all the accounts who can earn it.
     * @param $date date corresponding to de salary to register.
     * @param Rule $rule rule to follow to add the salary.
     */
    public static function paySalaries($date = null, $rule = null) {

        if ($date === null)
            $date = strtotime('today');

        if ($rule === null)
            $rule = Rule::getCurrentRule();

        $accounts = self::getUserAccounts();

        $positive = 0;
        $negative = 0;
        $positive_count = 0;
        $negative_count = 0;

        $penalties = 0;
        $compensation = 0;

        $dateLastPeriod = Period::getLastDate();

        //Count how many account in positive and in negative balance
        foreach ($accounts as $acc) {
            //uncomment in case we want all user to earn the salary
            //$acc->last_action = date('Y-m-d');
            $amount = $acc->earned - $acc->spended - $acc->balance;

            if ($amount < 0 && $dateLastPeriod <= $acc->last_action) { //count only active accounts
                $negative -= $amount;
                $negative_count++;
            } else if ($amount > 0) { //if positive, is active account
                $positive += $amount;
                $positive_count++;
            }
        }

        if ($negative_count == 0 || $positive_count == 0) {
            $negative_average = 0;
        } else {
            $negative_average = $negative / $negative_count;
        }


        //Assign penaltied salaries
        foreach ($accounts as $acc) {
            if ($dateLastPeriod <= $acc->last_action //(isset($acc->lastSalary) && $acc->lastSalary->executed_at <= $acc->last_action)
                    && (($acc->earned - $acc->spended - $acc->balance) < 0)) {
                $ret = $acc->addSalary($date, $rule, $negative_average);
                $penalties += $ret['penalty'];
            }
        }

        //Assign compensed salaries

        foreach ($accounts as $acc) {
            if ($dateLastPeriod > $acc->last_action) {
                continue;
            } else if (($acc->earned - $acc->spended - $acc->balance) >= 0) {
                $ret = $acc->addSalary($date, $rule, $positive, $penalties);
                $compensation += $ret['compensation'];
            }
        }

        //log penalties & compensations to check everything is shared
        //System accounts
        $accounts = self::getSystemAccounts();
        foreach ($accounts as $acc) {
            $ret = $acc->addSalary($date, $rule);
        }

        //Reset earned and spended
        $accounts = self::getTaxesAccounts();
        foreach ($accounts as $acc) {
            $amount = $acc->earned - $acc->spended - $acc->balance;
            $acc->saveAttributes(array(
                'earned' => 0,
                'spended' => 0,
                'balance' => ($amount > 0 ? 0 : -$amount)));
        }
    }

    /**
     * Return th account holder.
     */
    public function getHolder() {
        $holders = $this->holders;
        foreach ($holders as $holder) {
            return $holder;
        }
    }

    /**
     * Charge taxes to all accounts
     * @param Rule $rule The rule to charge taxes
     */
    public static function chargeTaxes(Rule $rule = null) {
        if ($rule == null) {
            $rule = Rule::getCurrentRule();
        }

        $accounts = self::getTaxesAccounts();

        $amount = 0;

        foreach ($accounts as $acc) {
            $ret = $acc->subTaxes($rule);
            $amount += $ret['taxes'];
        }

        //log the amount added to fund
    }

    public static function getUserAccounts() {
        return Account::model()->findAll('`class`=\'' . Account::CLASS_USER . '\' AND deleted is null');
    }

    public static function getTaxesAccounts() {
        return Account::model()->findAll('`class`!=\'' . Account::CLASS_FUND . '\' AND deleted is null');
    }

    public static function getSalaryAccounts() {
        return Account::model()->findAll('( class=\'' . Account::CLASS_USER . '\' OR class=\'' . Account::CLASS_SYSTEM . '\' ) AND deleted is null');
    }

    /**
     * Adap Fund money to fit the new rule
     * @param Rule $newRule The new rule to adapt fund.
     * @param Rule $currRule The rule to copy from.
     */
    public static function adaptFunds(Rule $newRule, Rule $currRule = null) {

        if ($currRule === null) {
            $currRule = Rule::getCurrentRule();
        }
        if ($newRule->salary == null) {
            $newRule->salary = $currRule->salary;
        }
        if ($newRule->min_salary == null) {
            $newRule->min_salary = $newRule->salary / Rule::MIN_SALARY_DIVIDER;
        }
        if ($newRule->multiplier == null) {
            $newRule->multiplier = $currRule->multiplier;
        }

        $rule = Rule::getAdaptedRule();

        $fund = Account::getFundAccount();
        $fund->credit += (($newRule->salary * $newRule->multiplier) - ($rule->salary * $rule->multiplier)) * count(Account::getSalaryAccounts());
        $fund->save();

        if ($newRule->isNewRecord) {
            $newRule->system_adapted = 1;
            $newRule->save();
        } else {
            $newRule->saveAttributes(array('system_adapted' => 1));
        }

        //Records Update
        $users = User::model()->findAll('deleted is NULL');
        $accounts = Account::model()->findAll();
        $total_amount = 0;
        foreach ($accounts as $account) {
            $total_amount += $account->credit;
        }

        Record::updateRecord(array('total_amount' => $total_amount, 'user_count' => count($users)));
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->_isNew = true;
            } else {
                $this->_isNew = false;
            }
            return true;
        } else
            return false;
    }

    protected function afterSave() {
        if ($this->_isNew) {
            $this->_isNew = false;

            //When new user account we add funds to system.
            if ($this->class == self::CLASS_USER) {
                $adaptedRule = Rule::getAdaptedRule();
                $rule = Rule::getCurrentRule();

                $systemAccs = self::getSystemAccounts();

                $fund = self::getFundAccount();
                $fund->credit += $adaptedRule->salary * ($adaptedRule->multiplier + count($systemAccs));
                $fund->save();

                //Add money to system accounts
                foreach ($systemAccs as $sysAcc) {
                    $tran = new Transaction;
                    $tran->charge_account = $fund->id;
                    $tran->deposit_account = $sysAcc->id;
                    $tran->charge_entity = self::FUND_ENTITY;
                    $tran->deposit_entity = self::FUND_ENTITY;
                    $tran->class = Transaction::CLASS_SALARY;
                    $tran->amount = $rule->salary;
                    $tran->subject = 'New user payment';
                    $tran->save();
                }
            }
        }
        parent::afterSave();
    }

    public static function getFundAccount() {
        return self::model()->find('class=\'' . self::CLASS_FUND . '\' AND deleted is NULL');
    }

    public static function getSystemAccounts() {
        return self::model()->findAll('class=\'' . self::CLASS_SYSTEM . '\' AND deleted is NULL');
    }

    /**
     * Reset account, set to 0 and when positive adds the different between\
     *  earned ans spended
     */
    public function reload() {
        $holders = $this->holders;
        foreach ($holders as $holder) {
            $charge_entity = $holder->id;
        }

        //set account to 0

        $to_zero = new Transaction;
        $to_zero->charge_account = $this->id;
        $to_zero->deposit_account = self::FUND_ACCOUNT;

        $to_zero->charge_entity = $charge_entity;
        $to_zero->deposit_entity = self::FUND_ENTITY;
        $to_zero->class = Transaction::CLASS_SYSTEM;
        $to_zero->amount = $this->credit;
        $to_zero->subject = Yii::t('app,', 'Account reset');
        $to_zero->save();


        $received = Transaction::model()->findBySql('select sum(`amount`) as `amount` from `' . Transaction::model()->tableSchema->name . '` where (`class` =\'' . Transaction::CLASS_CHARGE . '\' OR `class` = \'' . Transaction::CLASS_TRANSFER . '\') AND deposit_account = ' . $this->id);
        $spended = Transaction::model()->findBySql('select sum(`amount`) as `amount` from `' . Transaction::model()->tableSchema->name . '` where (`class` =\'' . Transaction::CLASS_CHARGE . '\' OR `class` = \'' . Transaction::CLASS_TRANSFER . '\') AND charge_account = ' . $this->id);

        //Adds the possitivediference to the account
        $amount = $received->amount - $spended->amount;
        $reset = new Transaction;
        $reset->charge_account = self::FUND_ACCOUNT;
        $reset->deposit_account = $this->id;
        $reset->charge_entity = self::FUND_ENTITY;
        $reset->deposit_entity = $charge_entity;
        $reset->class = Transaction::CLASS_SYSTEM;
        $reset->amount = ($amount > 0 ? $amount : 0);
        $reset->subject = Yii::t('app', 'Earned ({earned}) - Expended ({expended}) = {total}', array(
                    '{earned}' => Transaction::amountSystemToUser($received->amount),
                    '{expended}' => Transaction::amountSystemToUser($spended->amount),
                    '{total}' => Transaction::amountSystemToUser($amount)));
        $reset->save();
    }

    public function rollbackSalary() {
        foreach ($this->holders as $holder) {
            $charge_entity = $holder->id;
        }

        $rb = new Transaction;
        $rb->charge_account = $this->id;
        $rb->deposit_account = Account::FUND_ACCOUNT;

        $rb->charge_entity = $charge_entity;
        $rb->deposit_entity = Account::FUND_ENTITY;
        $rb->class = Transaction::CLASS_SYSTEM;
        $rb->amount = $this->lastSalary->amount;
        $rb->subject = Yii::t('app,', 'Rollback salary and try again.');
        $rb->save();

        $earned = Transaction::model()->findBySql('select sum(`amount`) as `amount` from `' . Transaction::model()->tableSchema->name . '` where (`class` =\'' . Transaction::CLASS_CHARGE . '\' OR `class` = \'' . Transaction::CLASS_TRANSFER . '\') AND deposit_account = ' . $this->id . ' AND executed_at > \'' . Period::getPrevious()->added . '\' and executed_at < \'' . $this->lastSalary->executed_at . '\'');
        $spended = Transaction::model()->findBySql('select sum(`amount`) as `amount` from `' . Transaction::model()->tableSchema->name . '` where (`class` =\'' . Transaction::CLASS_CHARGE . '\' OR `class` = \'' . Transaction::CLASS_TRANSFER . '\') AND charge_account = ' . $this->id . ' AND executed_at > \'' . Period::getPrevious()->added . '\' and executed_at < \'' . $this->lastSalary->executed_at . '\'');

        $this->saveAttributes(array('earned' => $earned->amount, 'spended' => $spended->amount));
    }

}

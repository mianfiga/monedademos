<?php

/**
 * This is the model class for table "{{transaction}}".
 *
 * The followings are the available columns in table '{{transaction}}':
 * @property string $id
 * @property string $executed_at
 * @property string $class
 * @property string $amount
 * @property string $charge_account
 * @property string $deposit_account
 * @property string $charge_user
 * @property string $deposit_user
 * @property string $subject
 *
 * The followings are the available model relations:
 * @property Account $chargeAccount
 * @property Account $depositAccount
 * @property User $chargeUser
 * @property User $depositUser
 */
class Transaction extends TransactionBase {

    private $db_transaction;

    const CLASS_SALARY = 'salary'; // 1;
    const CLASS_TAX = 'tax'; //2;
    const CLASS_TRANSFER = 'transfer'; //3;
    const CLASS_CHARGE = 'charge'; //4;
    const CLASS_MOVEMENT = 'movement'; //5;
    const CLASS_SYSTEM = 'system';
    const CLASS_REFUND = 'refund';
    const CLASS_SYSTEM_REFUND = 'system refund';
    const USER_PRECISION = 2;
    const SYSTEM_PRECISION = 5;

    public $charge_account_number;
    public $deposit_account_number;
    public $sid;
    public $amount_converted = false;
    public $form_amount;
    public $refered_pending;
    public $charge_errors = 0;
    public $deposit_errors = 0;
    public $foreign_account_number;
    public $is_payment;

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
            array('form_amount', 'required', 'on' => 'form'),
            array('form_amount', 'length', 'max' => 20, 'on' => 'form'),
            array('form_amount', 'match', 'pattern' => '/^\d+(\.\d{2})?$/', 'message' => '{attribute} is invalid. Use: # or #.##', 'on' => 'form'),
            array('charge_account_number, deposit_account_number, sid', 'required', 'on' => 'form'),
            array('charge_account_number, deposit_account_number', 'check_account_number', 'on' => 'form'),
            array('subject', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, class, amount, charge_account, deposit_account, charge_user, deposit_user, subject', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'chargeAccount' => array(self::BELONGS_TO, 'Account', 'charge_account'),
            'depositAccount' => array(self::BELONGS_TO, 'Account', 'deposit_account'),
            'chargeEntity' => array(self::BELONGS_TO, 'Entity', 'charge_entity'),
            'depositEntity' => array(self::BELONGS_TO, 'Entity', 'deposit_entity'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('app', 'ID'),
            'executed_at' => Yii::t('app', 'Date'),
            'class' => Yii::t('app', 'Class'),
            'form_amount' => Yii::t('app', 'Amount'),
            'charge_account_number' => Yii::t('app', 'Charge Account Number (Source)'),
            'deposit_account_number' => Yii::t('app', 'Deposit Account Number (Destination)'),
//            'charge_account' => Yii::t('app', 'Charge Account (Source)'),
//            'deposit_account' => Yii::t('app', 'Deposit Account (Destination)'),
            'foreign_account_number' => Yii::t('app', 'Account'),
            'amount' => Yii::t('app', 'Amount'),
            'subject' => Yii::t('app', 'Subject'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $account_number = Yii::app()->session['accountNumber'];
        $entity_id = Yii::app()->user->getId();
        if ($account_number == null) {
            $accounts = Authorization::getByEntity($entity_id /* ,'class='.Authorization::CLASS_HOLDER */);
            foreach ($accounts as $account) {
                $account_number = $account->getAccountNumber();
            }
            Yii::app()->session['accountNumber'] = $account_number;
        }

        $acc = Authorization::splitAccountNumber($account_number);

        $criteria = new CDbCriteria;

        $criteria->compare('class', $this->class, true);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('charge_account_number', $this->charge_account_number, true);
        $criteria->compare('deposit_account_number', $this->deposit_account_number, true);

        $criteria->order = 'id DESC'; // last_login DESC,
        $criteria->condition = "(charge_account='" . $acc['account_id'] . "')" . /* AND charge_entity='".$acc['entity_id']."')". */
                " OR (deposit_account='" . $acc['account_id'] . "')"; /* AND deposit_entity='".$acc['entity_id']."')", */

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 30,
            ),
        ));
    }

    public function getUrl() {
        return Yii::app()->createUrl('transaction/view', array(
                    'id' => $this->id,
        ));
    }

    protected function afterFind() {
        parent::afterFind();

        $this->charge_account_number = Authorization::formAccountNumber($this->charge_entity, $this->charge_account);
        $this->deposit_account_number = Authorization::formAccountNumber($this->deposit_entity, $this->deposit_account);

        //info to display sign in the transaction list
        if (isset(Yii::app()->session)) {
            $account_number = Yii::app()->session['accountNumber'];
            $acc = Authorization::splitAccountNumber($account_number);

            if ($acc['account_id'] == $this->charge_account) {
                $this->foreign_account_number = $this->deposit_account_number;
                $this->is_payment = true;
            } else {
                $this->foreign_account_number = $this->charge_account_number;
                $this->is_payment = false;
            }
        }
    }
    protected function beforeValidate(){
        $this->form_amount = str_replace(',','.',str_replace(' ','',$this->form_amount));
        $this->charge_account_number = str_replace(' ','',$this->charge_account_number);
        $this->deposit_account_number = str_replace(' ','',$this->deposit_account_number);
        return parent::beforeValidate();
    }
    protected function afterValidate() {
        if ($this->isNewRecord) {
            if ($this->getScenario() == 'form') {
                if (!$this->amount_converted) {
                    $this->amount = $this->getSystemAmount();
                }

                if (($charge = Authorization::model()->splitAccountNumber($this->charge_account_number)) != null) {
                    $this->charge_entity = $charge['entity_id'];
                    $this->charge_account = $charge['account_id'];
                }
                if (($deposit = Authorization::model()->splitAccountNumber($this->deposit_account_number)) != null) {
                    $this->deposit_entity = $deposit['entity_id'];
                    $this->deposit_account = $deposit['account_id'];
                }
            }
        }
        parent::afterValidate();
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                if ($this->getScenario() == 'form') {
                    if (!$this->amount_converted) {
                        $this->amount = $this->getSystemAmount();
                    }
                    if (($charge = Authorization::model()->splitAccountNumber($this->charge_account_number)) != null) {
                        $this->charge_entity = $charge['entity_id'];
                        $this->charge_account = $charge['account_id'];
                    }
                    if (($deposit = Authorization::model()->splitAccountNumber($this->deposit_account_number)) != null) {
                        $this->deposit_entity = $deposit['entity_id'];
                        $this->deposit_account = $deposit['account_id'];
                    }
                }

                $this->executed_at = date('YmdHis');

                if ($this->charge_account == $this->deposit_account) {
                    $this->class = self::CLASS_MOVEMENT;
                }


////////////////////////////////////////////////////
                $charge = Account::model()->findByPk($this->charge_account);
                $deposit = Account::model()->findByPk($this->deposit_account);

                $this->charge_errors = 0;
                if ($charge === null) {
                    $this->charge_errors += Account::ERROR_WRONG;
                }
                if ($charge->blocked !== null) {
                    $this->charge_errors += Account::ERROR_BLOCKED;
                }
                if ($charge->deleted !== null) {
                    $this->charge_errors += Account::ERROR_DELETED;
                }
                if ($charge->id != Account::FUND_ACCOUNT && $charge->credit < $this->amount) {
                    $this->charge_errors += Account::ERROR_NOFUNDS;
                }

                $this->deposit_errors = 0;
                if ($deposit === null) {
                    $this->deposit_errors += Account::ERROR_WRONG;
                }
                if ($deposit->blocked !== null) {
                    $this->deposit_errors += Account::ERROR_BLOCKED;
                }
                if ($deposit->deleted !== null) {
                    $this->deposit_errors += Account::ERROR_DELETED;
                }

                if ($this->charge_errors == 0 && $this->deposit_errors == 0) {
                    $this->db_transaction = Yii::app()->db->beginTransaction();
                    try {
                        if ($this->class == self::CLASS_TRANSFER || $this->class == self::CLASS_CHARGE) {
                            $charge->spended += $this->amount;
                            $charge->total_spended += $this->amount;
                        } else if ($this->class == self::CLASS_REFUND) {
                            $charge->earned -= $this->amount;
                            $charge->total_earned -= $this->amount;
                        }//else if($this->class == self::CLASS_SYSTEM_REFUND) we do nothing in this case as long as salaries don't count

                        $charge->credit -= $this->amount;
                        $charge->save();

                        $deposit = Account::model()->findByPk($this->deposit_account);
                        if ($this->class == self::CLASS_TRANSFER || $this->class == self::CLASS_CHARGE) {
                            $deposit->earned += $this->amount;
                            $deposit->total_earned += $this->amount;
                            $rule = Rule::getCurrentRule();
                            if ($deposit->earned >= $rule->min_salary) {
                                $deposit->balance = 0;
                            }
                        } else if ($this->class == self::CLASS_REFUND) {
                            $deposit->spended -= $this->amount;
                            $deposit->total_spended -= $this->amount;
                        } //else if($this->class == self::CLASS_SYSTEM_REFUND) we do nothing in this case as long as salaries don't count
                        $deposit->credit += $this->amount;
                        $deposit->save();

                        $charge_fund = Account::model()->findByAttributes(array('class' => Account::CLASS_FUND, 'tribe_id' => $charge->tribe_id));
                        $charge_fund->credit += $this->amount;
                        $charge_fund->save();

                        $deposit_fund = Account::model()->findByAttributes(array('class' => Account::CLASS_FUND, 'tribe_id' => $deposit->tribe_id));
                        $deposit_fund->credit -= $this->amount;
                        $deposit_fund->save();

                        $tribe_balance = TribeBalance::get($charge->tribe_id, $deposit->tribe_id);
                        $tribe_balance->period_amount += $this->amount;
                        $tribe_balance->total_amount += $this->amount;
                        $tribe_balance->save();

                        $this->charge_tribe = $charge->tribe_id;
                        $this->deposit_tribe = $deposit->tribe_id;
                        return true;
                    } catch (Exception $e) {
                        $this->db_transaction->rollBack();
                        return false;
                    }
                } else {
                    if ($this->charge_errors != 0) {
                        ActivityLog::add($this->charge_entity, ActivityLog::E_TRANSACTION, 'ER-' . $this->charge_errors . '-' . $this->charge_account);
                    }
                    if ($this->deposit_errors != 0) {
                        ActivityLog::add($this->deposit_entity, ActivityLog::E_TRANSACTION, 'ER-' . $this->deposit_errors . '-' . $this->deposit_account);
                    }

                    return false;
                }

////////////////////////////////////////////////////
            }
            return true;
        } else
            return false;
    }

    protected function afterSave() {
        $this->db_transaction->getActive() && $this->db_transaction->commit();
        parent::afterSave();
        if ($this->refered_pending != null) {
            $pending = Pending::model()->findByPk($this->refered_pending);
            $pending->delete();
        }

        $notif_data = array('{amount}' => self::amountSystemToUser($this->amount),
            '{charge_account_number}' => Authorization::formAccountNumber($this->charge_entity, $this->charge_account),
            '{charge_user_name}' => $this->chargeEntity->name,
            '{deposit_account_number}' => Authorization::formAccountNumber($this->deposit_entity, $this->deposit_account),
            '{deposit_user_name}' => $this->depositEntity->name,
            '{subject}' => $this->subject);

        switch ($this->class) {
            case Transaction::CLASS_TRANSFER:
            case Transaction::CLASS_CHARGE:
                $entity_id = Yii::app()->user->getId();
                $this->chargeEntity->getObject()->saveAttributes(array('last_action' => date('YmdHis')));
                $this->depositEntity->getObject()->saveAttributes(array('last_action' => date('YmdHis')));
                $this->chargeAccount->saveAttributes(array('last_action' => date('YmdHis')));
                $this->depositAccount->saveAttributes(array('last_action' => date('YmdHis')));

                if ($entity_id == $this->charge_entity) {
                    Notification::addNotification(Notification::PAYMENT, $this->deposit_entity, Sid::getSID($this), $notif_data);
                    Notification::addNotification(Notification::SELF_CHARGE, $this->charge_entity, Sid::getSID($this), $notif_data);
                } else {
                    Notification::addNotification(Notification::SELF_PAYMENT, $this->deposit_entity, Sid::getSID($this), $notif_data);
                    Notification::addNotification(Notification::CHARGE, $this->charge_entity, Sid::getSID($this), $notif_data);
                }

                break;
            case Transaction::CLASS_SALARY:

                if ($this->amount == 0) {
                    Notification::addNotification(Notification::RECIPROCITY_LACK, $this->deposit_entity, Sid::getSID($this), $notif_data);
                } else if ($this->depositAccount->firstSalary->id == $this->id) {
                    Notification::addNotification(Notification::FIRST_SALARY, $this->deposit_entity, Sid::getSID($this), $notif_data);
                } else if ($this->depositAccount->total_earned == 0) {
                    Notification::addNotification(Notification::NEVER_SELL, $this->deposit_entity, Sid::getSID($this), $notif_data);
                } else {
                    Notification::addNotification(Notification::SALARY, $this->deposit_entity, Sid::getSID($this), $notif_data);
                }

                break;

            case Transaction::CLASS_SYSTEM:
                Notification::addNotification(Notification::SYSTEM, $this->deposit_entity, Sid::getSID($this), $notif_data);
                Notification::addNotification(Notification::SYSTEM, $this->charge_entity, Sid::getSID($this), $notif_data);
                break;
        }

        if (isset($entity_id)) {
            ActivityLog::add($entity_id, ActivityLog::TRANSACTION, Sid::getSID($this));
        }
    }

    public function check_account_number($attribute, $params) {
        $this->$attribute = strtoupper($this->$attribute);
        if (!Authorization::isValidAccountNumber($this->$attribute)) {
            $this->addError($attribute, 'Invalid Account Number'); //bonito lugar para añadir una entrada al futuro log
            return;
        }
    }

    static public function amountUserToSystem($amount) {
        $i = strpos($amount, '.');
        $amount = substr($amount, 0, $i + self::USER_PRECISION + 1);
        if ($i !== false)
            $i = strlen($amount) - $i - 1;
        else
            $i = self::USER_PRECISION;

        $i += self::SYSTEM_PRECISION - self::USER_PRECISION;

        $return = $amount;
        for (; $i > 0; $i--) {
            $return = $return * 10;
        }
        return $return;
    }

    static public function amountSystemToUserNo($amount) {
        $return = (float) $amount;
        for ($i = self::SYSTEM_PRECISION; $i > 0; $i--) {
            $return = $return / 10.0;
        }
        return number_format($return, self::USER_PRECISION);
    }

    static public function amountSystemToUser($amount) {
        return self::amountSystemToUserNo($amount) . ' đ';
    }

    public function getSystemAmount() {
        if ($this->getScenario() == 'form' && !$this->amount_converted && $this->isNewRecord) {
            $this->amount = self::amountUserToSystem($this->form_amount);
            $this->amount_converted = true;
        }
        return $this->amount;
    }

    public function getAmount() {
        return self::amountSystemToUser($this->getSystemAmount());
    }

    public static function convertDatetime($str) {
        list($date, $time) = explode(' ', $str);
        list($year, $month, $day) = explode('-', $date);
        list($hour, $minute, $second) = explode(':', $time);

        $timestamp = mktime($hour, $minute, $second, $month, $day, $year);

        return $timestamp;
    }

    public static function translateAction($action) {
        switch ($action) {
            case 'salary':
                return Transaction::CLASS_SALARY;
            case 'transfer':
                return Transaction::CLASS_TRANSFER;
            case 'charge':
                return Transaction::CLASS_CHARGE;
            case 'movement':
                return Transaction::CLASS_MOVEMENT;
        }
    }

    public function getChargeAccountNumber() {
        if ($this->charge_account_number != null)
            return $this->charge_account_number;
        return Authorization::formAccountNumber($this->charge_entity, $this->charge_account);
    }

    public function getDepositAccountNumber() {
        if ($this->deposit_account_number != null) {
            return $this->deposit_account_number;
        }
        return Authorization::formAccountNumber($this->deposit_entity, $this->deposit_account);
    }

    public static function actionsToTxt($action) {
        switch ($action) {
            case 'salary':
                return Yii::t('app', 'salary');
            case 'transfer':
                return Yii::t('app', 'payment');
            case 'charge':
                return Yii::t('app', 'charge');
            case 'movement':
                return Yii::t('app', 'movement');
        }
    }

}

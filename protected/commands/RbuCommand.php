<?php

class RbuCommand extends CConsoleCommand {

    public function actionUserEntity() {
        $users = User::model()->findAll();
        foreach ($users as $user) {
            $ent = new Entity;
            $ent->class = get_class($user);
            $ent->object_id = $user->id;
            $ent->save();
        }
    }

    public function actionSetTotalAmounts() {
        $accounts = Account::model()->findAll();
        foreach ($accounts as $account) {
            $received = Transaction::model()->findBySql('select sum(`amount`) as `amount`, count(*) as subject from `' . Transaction::model()->tableSchema->name . '` where (`class` =\'' . Transaction::CLASS_CHARGE . '\' OR `class` = \'' . Transaction::CLASS_TRANSFER . '\') AND deposit_account = ' . $account->id);
            $spended = Transaction::model()->findBySql('select sum(`amount`) as `amount`, count(*) as subject from `' . Transaction::model()->tableSchema->name . '` where (`class` =\'' . Transaction::CLASS_CHARGE . '\' OR `class` = \'' . Transaction::CLASS_TRANSFER . '\') AND charge_account = ' . $account->id);
            $account->total_earned = $received->amount;
            $account->total_spended = $spended->amount;
            $account->deposit_transfer_count = $received->subject;
            $account->charge_transfer_count = $spended->subject;
            $account->save();
        }
    }

    public function actionSetRiskEstimation() {
        $activities = ActivityLog::model()->findAll('1 order by id ASC');
        foreach ($activities as $act) {

            $act->riskEstimation();
            echo $act->id . ': ' . $act->risk_estimation ."\n";
            $act->save();
        }
    }

    public function actionSetEntropy() {
        $margin = 0.9;
        $accounts = Account::model()->findAll();
        $global_clients = array();
        $global_sellers = array();

        foreach ($accounts as $account) {

            $clients_transactions = Transaction::model()->findAllBySql('select deposit_account, charge_account, sum(amount) as `amount` FROM `rbu_transaction` WHERE (`class` =\'' . Transaction::CLASS_CHARGE . '\' OR `class` = \'' . Transaction::CLASS_TRANSFER . '\') AND deposit_account = ' . $account->id . ' group by charge_account order by amount desc');
            $sellers_transactions = Transaction::model()->findAllBySql('select charge_account, deposit_account, sum(amount) as `amount` FROM `rbu_transaction` WHERE (`class` =\'' . Transaction::CLASS_CHARGE . '\' OR `class` = \'' . Transaction::CLASS_TRANSFER . '\') AND charge_account = ' . $account->id . ' group by deposit_account order by amount desc');
            $account->total_clients = count($clients_transactions);
            $account->total_sellers = count($sellers_transactions);

            $i = 0;
            $acc = 0.0;
            foreach ($clients_transactions as $client) {
                $acc += $client->amount;
                $global_clients[$i] = (isset($global_clients[$i]) ? $global_clients[$i] : 0) + $client->amount;
                $i++;
                if ($acc / $account->total_earned > $margin) {
                    $account->best_clients = $i;
                    break;
                }
            }

            $i = 0;
            $acc = 0.0;
            foreach ($sellers_transactions as $seller) {
                $acc += $seller->amount;
                $global_sellers[$i] = (isset($global_sellers[$i]) ? $global_sellers[$i] : 0) + $seller->amount;
                $i++;
                if ($acc / $account->total_spended > $margin) {
                    $account->best_sellers = $i;
                    break;
                }
            }
            $account->save();
        }
        echo 'Total_sum = ' . count($accounts) . "\n";
        echo "N;Global_clients;Global_sellers\n";
        for ($i = 0; isset($global_sellers[$i]); $i++) {
            echo $i . ';' . $global_clients[$i] . ';' . $global_sellers[$i] . "\n";
        }
    }

    public function actionCorrectRecords() {
        $users = User::model()->findAll();
        $records = Record::model()->findAll();
        echo count($records);
        $record = current($records);
        foreach ($users as $user) {
            while ($record->user_count < ($user->id - 1)) {
                $record = next($records);
            }

            if ($record->user_count == ($user->id - 1)) {
                $record->added = $user->created;
                $record->save();
                echo 'id:' . $record->id . "\n";
            }
        }
    }

    //rollbackPeriod()
    public function actionRollbackPeriod($date = null) {
        if ($date == null) {
            $date = date(Common::DATETIME_FORMAT, strtotime('today'));
        }
        $prePeriod = Period::getPrevious();
        $period = Period::getLast();
        $lastDate = $period->added;
        $period->saveAttributes(array('added' => $prePeriod->added));
        echo $date . "\n";
        $accounts = Account::getSalaryAccounts();
        foreach ($accounts as $account) {
            if ($account->lastSalary->executed_at < $date) {
                continue;
            }

            //return salary
            $account->rollbackSalary();
        }

        Account::paySalaries();
        $period->saveAttributes(array('added' => $lastDate));
    }
    
    public function actionSystemTransaction($amount, $subject, $charge_account=1, $deposit_account=1, $charge_entity=1, $deposit_entity=1) {    
        $rb = new Transaction;
        $rb->charge_account = $charge_account;
        $rb->deposit_account = $deposit_account;

        $rb->charge_entity = $charge_entity;
        $rb->deposit_entity = $deposit_entity;
        $rb->class = Transaction::CLASS_SYSTEM;
        $rb->amount = $amount;
        $rb->subject = $subject;
        $rb->save();    
    }
    
    public function actionPaySalaries($date = null) {
        if ($date == null) {
            $date = strtotime('first day of this month');
        }
        $rule = Rule::getCurrentRule();
        Account::paySalaries($date, $rule);
    }

    //reload
    public function actionReload($date = null) {
        $accounts = Account::getSalaryAccounts();
        foreach ($accounts as $account) {
            $account->reload();
        }
    }

    //ChargeTaxes and paySalaries
    public function actionAddPeriod($date = null) {
        if ($date == null) {
            $date = strtotime('first day of this month');
        }

        $rule = Rule::getCurrentRule();
        $period = Period::calculate();
        Account::chargeTaxes($rule);
        Account::paySalaries($date, $rule);
        Rule::addPeriodRule($period);
        $period->save();
    }

    public function actionAddSalary($account, $date = null) {
        if ($date == null) {
            $date = mktime (0,0,0, date("n"),1);
        }
        $acc = Account::model()->findByPk($account);
        $acc->addSalary($date);
    }

    public function actionNewRule($date = null, $salary = null, $min_salary = null, $multiplier = null) {
        if ($date == null) {
            $date = time();
        }

        $rule = Rule::getCurrentRule();

        $newRule = new Rule;
        $newRule->salary = $salary;
        $newRule->min_salary = $min_salary;
        $newRule->multiplier = $multiplier;
        $newRule->added = date('YmdHis');
        $newRule->save();

        //Contamos las cuentas con sueldo y adaptamos la cantidad del fondo.
        Account::adaptFunds($newRule);
    }

    public function actionPeriodCalculate() {
        $period = Period::calculate();
        echo 'Active Users: ' . $period->active_users . "\n";
        echo 'Movements   : ' . $period->movements . "\n";
    }

    public function actionNotificationsInstantCheck($date = null) {
        //executes every 5 minutes.
        //send email/push notifications.
        if ($date == NULL) {
            $date = date('Y-m-d H:i:s');
        }
        Notification::notify();
    }

    public function actionNotificationsDailyCheck($date = null) {
        //delete old notifications, check for expired ads, Adapt fund to new rule
        if ($date == NULL) {
            $date = date('Y-m-d');
        }

        $notifs = NotificationMessage::model()->findAll('updated < DATE_SUB(CURDATE(), INTERVAL ' . Notification::EXPIRATION . ' SECOND)');
        foreach ($notifs as $notif) {
            $notif->delete();
        }

        $ads = MarketAd::model()->findAll('visible = 1 AND expiration = CURDATE()+' . MarketAd::EXPIRATION_PRENOTIFICATION_DAYS);
        foreach ($ads as $ad) {
            $notif_data = array();
            $notif_data['{title}'] = $ad->title;
            $notif_data['{id}'] = $ad->id;
            Notification::addNotification(Notification::MARKET_AD_EXPIRATION, $ad->created_by, Sid::getSID($ad), $notif_data);
        }

        $ads = MarketAd::model()->findAll('visible = 1 AND expiration = CURDATE()');
        foreach ($ads as $ad) {
            $notif_data = array();
            $notif_data['{title}'] = $ad->title;
            $notif_data['{id}'] = $ad->id;
            Notification::addNotification(Notification::MARKET_AD_EXPIRED, $ad->created_by, Sid::getSID($ad), $notif_data);
        }

        $newRule = Rule::getTomorrowRule();
        $rule = Rule::getAdaptedRule();
        if ($newRule->id != $rule->id) {
            //Contamos las cuentas con sueldo y adaptamos la cantidad del fondo.
            Account::adaptFunds($newRule);
        }
    }

    public function actionNotificationsPreMonthCheck($date = null) {
        //check if users have had no interaction, and notify them.
        if ($date == NULL) {
            $date = date('Y-m-d');
        }
    }

}

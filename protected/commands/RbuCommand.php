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
            $date = strtotime('first day of this month');
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

        $ads = MarketAd::model()->findAll('expiration = CURDATE()+' . MarketAd::EXPIRATION_PRENOTIFICATION_DAYS);
        foreach ($ads as $ad) {
            $notif_data = array();
            $notif_data['{title}'] = $ad->title;
            $notif_data['{id}'] = $ad->id;
            Notification::addNotification(Notification::MARKET_AD_EXPIRATION, $ad->created_by, Sid::getSID($ad), $notif_data);
        }

        $ads = MarketAd::model()->findAll('expiration = CURDATE()');
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

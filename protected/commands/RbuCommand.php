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
            echo $act->id . ': ' . $act->risk_estimation . "\n";
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

    public function actionSystemTransaction($amount, $subject, $charge_account = 1, $deposit_account = 1, $charge_entity = 1, $deposit_entity = 1) {
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

    public function actionSystemRefund($amount, $subject, $charge_account = 1, $deposit_account = 1, $charge_entity = 1, $deposit_entity = 1) {
        $rb = new Transaction;
        $rb->charge_account = $charge_account;
        $rb->deposit_account = $deposit_account;

        $rb->charge_entity = $charge_entity;
        $rb->deposit_entity = $deposit_entity;
        $rb->class = Transaction::CLASS_SYSTEM_REFUND;
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

    public function actionMigrate() {
        $modified_tribes = array();
        $tribes = Tribe::model()->findAll();
        foreach ($tribes as $tribe) {
            $fund_account = Account::getFundAccount($tribe->id);
            if (!$fund_account && count($tribe->migrations) > 3) {//Activate tribe: create accounts and rule
                $tribe->activate();
                $modified_tribes[$tribe->id] = true;
            }
        }

        $transaction = Yii::app()->db->beginTransaction();
        try {

            $migrations = TribeMigration::model()->findAll('status="confirmed" AND executed_at is null');
            foreach ($migrations as $migration) {
                $tribe = $migration->to;
                if (!Account::getFundAccount($tribe->id)) {
                    continue;
                }

                if ($migration->entity->class == get_class(User::model())) {
                    //Refund system money from source tribe
                    $source_sys_accs = Account::getSystemAccounts($migration->entity->tribe_id);
                    $source_fund_acc = Account::getFundAccount($migration->entity->tribe_id);

                    $source_tribe_entity = Entity::get($migration->entity->tribe);
                    $previous_migration = TribeMigration::model()->find('entity_id=' . $migration->entity_id . ' AND executed_at is not null order by id DESC');
                    if ($previous_migration) {
                        $last_migration_date = $previous_migration->executed_at;
                    } else {
                        $last_migration_date = $migration->entity->getObject()->created;
                    }
                    $last_migration_rule = Rule::getDateRule($last_migration_date, $migration->entity->tribe->group_id);


                    foreach ($source_sys_accs as $sys_acc) {
                        $system_refund = new Transaction;
                        $system_refund->charge_account = $sys_acc->id;
                        $system_refund->deposit_account = $source_fund_acc->id;
                        $system_refund->charge_entity = $source_tribe_entity->id;
                        $system_refund->deposit_entity = $source_tribe_entity->id;
                        $system_refund->class = Transaction::CLASS_SYSTEM_REFUND;
                        $system_refund->amount = $last_migration_rule->salary;
                        $system_refund->subject = 'Emigration';
                        $system_refund->save();
                    }

                    //take money related to this user out of the source tribe
                    $num_sys_accs = count($source_sys_accs);
                    $source_current_rule = Rule::getAdaptedRule($migration->entity->tribe->group_id);

                    $source_fund_acc = Account::getFundAccount($migration->entity->tribe_id);
                    $source_fund_acc->credit -= $last_migration_rule->salary * $num_sys_accs + $source_current_rule->salary * $source_current_rule->multiplier;
                    $source_fund_acc->save();

                    //put money in the destination tribe fund account
                    $destination_current_rule = Rule::getAdaptedRule($tribe->group_id);
                    $destination_sys_accs = Account::getSystemAccounts($tribe->id);
                    $num_sys_accs = count($destination_sys_accs);
                    $destination_fund_acc = Account::getFundAccount($tribe->id);
                    $destination_fund_acc->credit += $destination_current_rule->salary * $num_sys_accs + $destination_current_rule->salary * $destination_current_rule->multiplier;
                    $destination_fund_acc->save();

                    $destination_tribe_entity = Entity::get($tribe);

                    //add money to system account
                    foreach ($destination_sys_accs as $sys_acc) {
                        $system_new_user = new Transaction;
                        $system_new_user->charge_account = $destination_fund_acc->id;
                        $system_new_user->deposit_account = $sys_acc->id;
                        $system_new_user->charge_entity = $destination_tribe_entity->id;
                        $system_new_user->deposit_entity = $destination_tribe_entity->id;
                        $system_new_user->class = Transaction::CLASS_SALARY;
                        $system_new_user->amount = $destination_current_rule->salary;
                        $system_new_user->subject = 'Inmigration, new user';
                        $system_new_user->save();
                    }
                }

                //moving accounts to new tribe
                foreach ($migration->entity->holdingAccounts as $account) {
                    $tribe_balance = TribeBalance::get($account->tribe_id, $tribe->id);
                    $balance = $account->earned - $account->spended - $account->balance;
                    $tribe_balance->period_amount += $balance;
                    $tribe_balance->total_amount += $balance;
                    $tribe_balance->save();

                    $account->tribe_id = $tribe->id;
                    $account->save();
                }

                //exceptionally we also add active market ads to the new island
                $ads = $migration->entity->marketAdCreator;
                foreach ($ads as $ad) {
                    if ($ad->expiration < Common::date()) {
                        continue;
                    }
                    $ad_tribe = new MarketAdTribe;
                    $ad_tribe->ad_id = $ad->id;
                    $ad_tribe->tribe_id = $tribe->id;
                    $ad_tribe->save();
                }

                $modified_tribes[$migration->entity->tribe_id] = true;
                $modified_tribes[$tribe->id] = true;

                $migration->entity->tribe_id = $tribe->id;
                $migration->entity->save();

                $migration->executed_at = Common::datetime();
                $migration->save();
            }

            foreach ($modified_tribes as $tribe_id => $modified) {
                if (!$modified) {
                    continue;
                }
                //Records Update
                $users = User::model()->with('entity')->findAll('entity.tribe_id = \'' . $tribe_id . '\' AND deleted is NULL');
                $accounts = Account::model()->findAll('tribe_id = \'' . $tribe_id . '\'');
                $total_amount = 0;
                foreach ($accounts as $account) {
                    $total_amount += $account->credit;
                }

                Record::updateRecord(array('total_amount' => $total_amount, 'user_count' => count($users)), $tribe_id);
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            die($e->getMessage());
        }
    }

    //ChargeTaxes and paySalaries
    public function actionAddPeriod($date = null) {
        $this->actionUpdateLastTransaction();
        $this->actionMigrate();

        if ($date == null) {
            $date = strtotime('first day of this month');
        }

        $tribes = Tribe::model()->findAll();

        $transaction = Yii::app()->db->beginTransaction();
        try {

            $period = array();
            $ret = array();
            foreach ($tribes as $tribe) {
                if (!Account::getFundAccount($tribe->id)) {
                    continue;
                }
                $rule = Rule::getCurrentRule($tribe->group_id);
                $period[$tribe->id] = Period::calculate($tribe->id);
                Account::chargeTaxes($tribe, $rule);

                $ret[$tribe->id] = Account::preSalaryData($tribe);
                $period[$tribe->id]['negative_accounts'] = $ret[$tribe->id]['negative_accounts'];
                $period[$tribe->id]['negative_amount'] = $ret[$tribe->id]['negative_amount'];
                $period[$tribe->id]['positive_accounts'] = $ret[$tribe->id]['positive_accounts'];
                $period[$tribe->id]['positive_amount'] = $ret[$tribe->id]['positive_amount'];

                $ret[$tribe->id]['penalties'] = Account::payPenalizedSalaries($tribe, $ret[$tribe->id], $date, $rule);
            }

            $tribes2 = $tribes;
            //Add penalties from other tribes
            foreach ($tribes as $from) {
                foreach ($tribes2 as $to) {
                    $balance = TribeBalance::getPeriodBalance($from->id, $to->id);
                    if ($balance > 0) {
                        $ret[$from->id]['penalties'] += $ret[$to->id]['penalties'] * ($balance / $ret[$to->id]->positive_amount);
                    }
                }
            }

            foreach ($tribes as $tribe) {
                if (!Account::getFundAccount($tribe->id)) {
                    continue;
                }
                $rule = Rule::getCurrentRule($tribe->group_id);
                Account::payCompensatedSalaries($tribe, $ret[$tribe->id], $date, $rule);
            }
            
            foreach ($period as $per) {
                $per->save();
            }

            Account::postSalariesReset();
            $groups = TribeGroup::model()->findAll();
            foreach ($groups as $group) {
                Rule::addTribeGroupRule($group->id);
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage();
            echo $e->getTraceAsString();
        }
    }

    public function actionUpdateLastTransaction() {
        $date = Period::getLastDate();
        $entities = Entity::model()->findAll(); //->with('lastChargeTransaction','lastDepositTransaction')->findAll('lastChargeTransaction.executed_at >= \''.$date.'\' OR lastDepositTransaction.executed_at >= \''.$date.'\'')-;
        foreach ($entities as $entity) {
            $entity->saveAttributes(array('last_transaction' => max((!$entity->lastChargeTransaction ? 0 : $entity->lastChargeTransaction->executed_at), (!$entity->lastDepositTransaction ? 0 : $entity->lastDepositTransaction->executed_at))));
        }
    }

    public function actionAddSalary($account, $date = null) {
        if ($date == null) {
            $date = mktime(0, 0, 0, date("n"), 1);
        }
        $acc = Account::model()->findByPk($account);
        $acc->addSalary($date);
    }

    public function actionNewRule($tribe_group_id, $date = null, $salary = null, $min_salary = null, $multiplier = null) {
        if ($date == null) {
            $date = time();
        }

        $rule = Rule::getCurrentRule($tribe_id);

        $newRule = new Rule;
        $newRule->salary = $salary;
        $newRule->min_salary = $min_salary;
        $newRule->multiplier = $multiplier;
        $newRule->tribe_group_id = $tribe_group_id;
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

        $groups = TribeGroup::model()->findAll();
        foreach ($groups as $group) {
            $newRule = Rule::getTomorrowRule($group->id);
            $rule = Rule::getAdaptedRule($group->id);
            if ($newRule->id != $rule->id) {
                //Contamos las cuentas con sueldo y adaptamos la cantidad del fondo.
                Account::adaptFunds($newRule);
            }
        }
    }

    public function actionNotificationsPreMonthCheck($date = null) {
        //check if users have had no interaction, and notify them.
        if ($date == NULL) {
            $date = date('Y-m-d');
        }
    }

    public function actionMoveMarketAdsToCreatorsTribe() {
        $ads = MarketAd::model()->findAll();
        foreach ($ads as $ad) {
            if (count($ad->tribes) == 0 && $ad->createdBy->tribe_id != null) {
                $relation_tribe = new MarketAdTribe;
                $relation_tribe->ad_id = $ad->id;
                $relation_tribe->tribe_id = $ad->createdBy->tribe_id;
                $relation_tribe->save();
            }
        }
    }

}

<?php

class TransactionController extends Controller {

    private $_model;

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            /* 			array('allow',  // allow all users to perform
              'actions'=>array('index'),
              'users'=>array('*'),
              ), */
            array('allow', // allow authenticated user
                'actions' => array('index', 'list', 'view', /* 'create', */'charge', 'transfer', 'movement'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id, $charge_errors = 0, $deposit_errors = 0) {
        if ($id != null) {
            $entity_id = Yii::app()->user->getId();


            $model = $this->loadModel($id);
            $msid = Sid::getSID($model);
            
            $rate = new Rate();            
            $rate->fill($msid);
            $rate->alreadyExists();
            $rate->url = Yii::app()->request->url;
            if ($model->charge_entity == $entity_id || $model->deposit_entity == $entity_id) { //falta ver si la cuenta es pública
                Notification::shown($entity_id, $msid);
                $this->render('view', array(
                    'model' => $model,
                    'charge_errors' => $charge_errors,
                    'deposit_errors' => $deposit_errors,
                    'rate' => $rate,
                ));
                return;
            }
        }

        $this->render('view', array(
            'model' => null,
            'charge_errors' => $charge_errors,
            'deposit_errors' => $deposit_errors,
        ));
    }

    public function actionCharge() {
        $condition = 'class=' . Authorization::CLASS_HOLDER . ' OR class=' . Authorization::CLASS_AUTHORIZED;
        $this->actionFill('charge', null, Authorization::getAccountList(Yii::app()->user->getId(), $condition));
    }

    public function actionTransfer() {
        $condition = 'class=' . Authorization::CLASS_HOLDER . ' OR class=' . Authorization::CLASS_AUTHORIZED;
        $this->actionFill('transfer', Authorization::getAccountList(Yii::app()->user->getId(), $condition), null);
    }

    public function actionMovement() {
        $condition = 'class=' . Authorization::CLASS_HOLDER . ' OR class=' . Authorization::CLASS_AUTHORIZED;
        $accountlist = Authorization::getAccountList(Yii::app()->user->getId(), $condition);
        $this->actionFill('movement', $accountlist, $accountlist);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionFill($action = 'transfer', $charge_accounts = null, $deposit_accounts = null) {

        $model = new Transaction('form');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Transaction'])) {
            $model->attributes = $_POST['Transaction'];
            if ($model->validate()) {
                $array = Yii::app()->session['operations'];
                $array[$model->sid]['action'] = Yii::app()->session['operations'][$model->sid]['action'];
                $model->class = Transaction::translateAction($action);
                $array[$model->sid]['model'] = $model;

                Yii::app()->session['operations'] = $array;
                $this->redirect(array('authorization/confirm', 'sid' => $model->sid));
            }
            /* 			if($model->save())
              $this->redirect(array('view','id'=>$model->id)); */
        } else {
            $model->sid = time();
            $array = Yii::app()->session['operations'];
            $array[$model->sid]['action'] = $action;
            $array[$model->sid]['url'] = 'transaction/view';
            Yii::app()->session['operations'] = $array;
        }

        $this->render('fill', array(
            'model' => $model,
            'action' => $action,
            'charge_accounts' => $charge_accounts,
            'deposit_accounts' => $deposit_accounts
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionFront() {
        $this->redirect(array('transaction/index')); ///cambiar en los menus
    }

    /**
     * Lists all models.
     */
    public function actionIndex($list = false) {

        $entity_id = Yii::app()->user->getId();
        $entity = Entity::model()->findByPk($entity_id);

        $user = ($entity->class == 'User' ? $entity->getObject() : null);

        $account_number = null;

        if (isset(Yii::app()->session['accountNumber'])) {
            $account_number = Yii::app()->session['accountNumber'];
        }

        $form_model = new AccountNumberFilterForm;

        if (isset($_POST['AccountNumberFilterForm'])) {
            $form_model->attributes = $_POST['AccountNumberFilterForm'];
            if ($form_model->validate()) {
                $account_number = $form_model->account_number;
            }
        }

        $auth = null;

        if ($account_number != null) {
            $acc = Authorization::splitAccountNumber($account_number);
            if ($acc == null)
                $account_number = null;
            elseif ($acc['entity_id'] != $entity_id)
                $account_number = null;
            elseif (!$auth = Authorization::isValidAccountNumber($account_number))
                $account_number = null;
        }

        if ($account_number == null) {
            $accounts = Authorization::getByEntity($entity_id, 'class=' . Authorization::CLASS_HOLDER);
            foreach ($accounts as $account) {
                $auth = $account;
                $account_number = $account->getAccountNumber();
                if ($user) {
                    if ($user->salt == $account->salt && $user->password == $account->password)
                        Yii::app()->user->setFlash('error', Yii::t('app', 'For security reasons you need to set the Pin/password for the account {account}. You can do it in the "Edit account" link or clicking <a href="{edit_account_link}">here</a>', array('{account}' => $account_number,
                                    '{edit_account_link}' => Yii::app()->createUrl('authorization/update', array('id' => $account_number)),
                                        )
                                ));

                    if ($auth->account->class == Account::CLASS_USER) {
                        break;
                    }
                } else {
                    break;
                }
            }
        }

        Yii::app()->session['accountNumber'] = $account_number;

        //$acc = Authorization::splitAccountNumber($account_number);

        $account = $auth->account; //Account::model()->findByPk($acc['account_id']);
//        $auth = Authorization::getAuthorization($account_number);
        if ($auth->wrong_pass_count >= ConfirmForm::ATTEMPTS) {
            Yii::app()->user->setFlash('error', Yii::t('app', 'The account {account} is blocked reset account by clicking in the "Edit account" link or clicking <a href="{edit_account_link}">here</a>', array('{account}' => $account_number,
                        '{edit_account_link}' => Yii::app()->createUrl('authorization/update', array('id' => $account_number)),
                            )
                    ));
        }

        /* 		$dataProvider = new CActiveDataProvider('Transaction', array(
          'criteria' => array(
          'condition' => "(charge_account='".$acc['account_id']."' AND charge_user='".$acc['user_id']."')".
          " OR (deposit_account='".$acc['account_id']."' AND deposit_user='".$acc['user_id']."')",
          'order' => 'id DESC',
          ),
          )); */

        $this->render('index', array(
//			'dataProvider' => null,
            'form_model' => $form_model,
            'accountList' => Authorization::getAccountList($entity_id),
            'accountNumber' => $account_number,
            'account' => $account,
            'auth' => $auth,
        ));
    }

    public function actionList($id = null) {
//        $this->layout='//layouts/column1';

        $entity_id = Yii::app()->user->getId();
        $entity = Entity::model()->findByPk($entity_id);

        $user = ($entity->class == 'User' ? $entity->getObject() : null);
        $form_model = new AccountNumberFilterForm;

        $account_number = $id;

        if (isset($_POST['AccountNumberFilterForm'])) {
            $form_model->attributes = $_POST['AccountNumberFilterForm'];
            $form_model->validate();
            $account_number = $form_model->account_number;
        }
        if ($account_number == null && isset(Yii::app()->session['accountNumber'])) {
            $account_number = Yii::app()->session['accountNumber'];
        }

        $auth = null;
        if ($account_number != null) {
            $acc = Authorization::splitAccountNumber($account_number);
            if ($acc == null)
                $account_number = null;
            elseif ($acc['entity_id'] != $entity_id)
                $account_number = null;
            elseif (!$auth = Authorization::isValidAccountNumber($account_number))
                $account_number = null;
        }

        if ($account_number == null) {
            $accounts = Authorization::getByEntity($entity_id /* ,'class='.Authorization::CLASS_HOLDER */);
            foreach ($accounts as $account) {
                $auth = $account;
                $account_number = $account->getAccountNumber();

                if ($user) {
                    if ($user->salt == $account->salt && $user->password == $account->password)
                        Yii::app()->user->setFlash('error', Yii::t('app', 'For security reasons you need to set the Pin/password for the account {account}. You can do it in the "Edit account" link or clicking <a href="{edit_account_link}">here</a>', array('{account}' => $account_number,
                                    '{edit_account_link}' => Yii::app()->createUrl('authorization/update', array('id' => $account_number)),
                                        )
                                ));

                    if ($auth->account->class == Account::CLASS_USER) {
                        break;
                    }
                } else {
                    break;
                }
            }
        }



        Yii::app()->session['accountNumber'] = $account_number;
//		$form_model->account_number = $account_number;

        $acc = Authorization::splitAccountNumber($account_number);

        $account = Account::model()->findByPk($acc['account_id']);


        $model = new Transaction('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Transaction']))
            $model->attributes = $_GET['Transaction'];

//		$dataProvider = new CActiveDataProvider('Transaction', array(
//				'criteria' => array(
//						'condition' => "(charge_account='".$acc['account_id']."')". /* AND charge_user='".$acc['user_id']."')".*/
//								" OR (deposit_account='".$acc['account_id']."')",/* AND deposit_user='".$acc['user_id']."')",*/
//						'order' => 'id DESC',
//					),
//			));

        $this->render('index', array(
//			'dataProvider' => $dataProvider,
            'model' => $model,
            'form_model' => $form_model,
            'accountList' => Authorization::getAccountList($entity_id),
            'accountNumber' => $account_number,
            'account' => $account,
            'auth' => $auth,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Transaction('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Transaction']))
            $model->attributes = $_GET['Transaction'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        if ($this->_model === null) {
            /* 			if(Yii::app()->user->isGuest)     //Hay que filtral si la cuenta de cargo o de deposito pertenece a el usuario registrado o si alguna es una cuenta pública.
              $condition='charge_account='.Post::STATUS_PUBLISHED
              .' OR deposit_account='.Post::STATUS_ARCHIVED;
              else */
            $condition = '';
            $this->_model = Transaction::model()->findByPk($id, $condition);

            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $this->_model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'transaction-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /* 	public static function translateAction($action)
      {
      switch($action)
      {
      case 'salary':
      return Transaction::CLASS_SALARY;
      case 'transfer':
      return Transaction::CLASS_TRANSFER;
      case 'charge':
      return Transaction::CLASS_CHARGE;
      case 'movement':
      return Transaction::CLASS_MOVEMENT;
      }
      } */
}

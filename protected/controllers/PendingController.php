<?php

class PendingController extends Controller {

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
            /* 			array('allow',  // allow all users to perform 'index' and 'view' actions
              'actions'=>array(),
              'users'=>array('*'),
              ), */
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'checkout'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array(),
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
    public function actionView($id) {
        if ($id != null) {
            $user_id = Yii::app()->user->getId();

            $model = $this->loadModel($id);
            if ($model->charge_entity == $user_id || $model->deposit_entity == $user_id) { //falta ver si la cuenta es pública
                Notification::shown($user_id, Sid::getSID($model));
                $this->render('view', array(
                    'model' => $model,
                ));
                return;
            }
        }
        $this->render('view', array(
            'model' => null,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Pending;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Pending'])) {
            $model->attributes = $_POST['Pending'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Pending'])) {
            $model->attributes = $_POST['Pending'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
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

    /**
     * Lists all models.
     */
    public function actionIndex($id = null) {
        $entity_id = Yii::app()->user->getId();

        $form_model = new AccountNumberFilterForm;

        $account_number = $id;

        if (isset($_POST['TransactionFilterForm'])) {
            $form_model->attributes = $_POST['TransactionFilterForm'];
            $form_model->validate();
            $account_number = $form_model->account_number;
        }
        if ($account_number == null && isset(Yii::app()->session['accountNumber'])) {
            $account_number = Yii::app()->session['accountNumber'];
        }

        if ($account_number != null) {
            $acc = Authorization::splitAccountNumber($account_number);
            if ($acc == null)
                $account_number = null;
            elseif ($acc['entity_id'] != $entity_id)
                $account_number = null;
            elseif (!Authorization::isValidAccountNumber($account_number))
                $account_number = null;
        }

        if ($account_number == null) {
            $accounts = Authorization::getByEntity($entity_id /* ,'class='.Authorization::CLASS_HOLDER */);
            foreach ($accounts as $account) {
                $account_number = $account->getAccountNumber();
            }
        }

        Yii::app()->session['accountNumber'] = $account_number;

        $form_model->account_number = $account_number;
        $acc = Authorization::splitAccountNumber($account_number);

//		if($account == null) {
        $account = Account::model()->findByPk($acc['account_id']);
//        }


        $dataProvider = new CActiveDataProvider('Pending', array(
                    'criteria' => array(
                        'condition' => "(charge_account='" . $acc['account_id'] . "' AND charge_entity='" . $acc['entity_id'] . "')" .
                        " OR (deposit_account='" . $acc['account_id'] . "' AND deposit_entity='" . $acc['entity_id'] . "')",
                        'order' => 'id DESC',
                    ),
                ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'form_model' => $form_model,
            'accountList' => Authorization::getAccountList($entity_id),
            'accountNumber' => $account_number,
            'account' => $account,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Pending('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Pending']))
            $model->attributes = $_GET['Pending'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionCheckout($id) {
        $pending = $this->loadModel($id);
        $model = new Transaction;

        $model->setAttributes($pending->Attributes, false);
        $model->id = null;
        $model->sid = time();
        $model->refered_pending = $pending->id;
        $array = Yii::app()->session['operations'];
        $array[$model->sid]['action'] = 'transfer';
        $array[$model->sid]['url'] = 'transaction/view';
        $model->class = Transaction::translateAction('transfer');
        $array[$model->sid]['model'] = $model;
        Yii::app()->session['operations'] = $array;
        $this->redirect(array('authorization/confirm', 'sid' => $model->sid));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        if(!is_numeric($id)){
          throw new CHttpException(404, 'Access denied.');
        }
        $model = Pending::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pending-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

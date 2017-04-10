<?php

class UserController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
        ));
    }

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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('captcha', 'invited' /*,'create'*/, 'recovery', 'recoveryRequest'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'me', 'update', 'edit'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
		            'actions' => array('index', 'view', 'me', 'update', 'edit'),
                //'actions' => array('index', 'delete', 'admin'),
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
      $this->layout = '//layouts/column1';
        //if (Yii::app()->user->getId() == $id) {
            $entity = Entity::model()->findByPk($id);

            if ($entity === null || $entity->object->deleted) {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
            if ($entity->class != 'User') {
                $this->redirect(array(strtolower($entity->class) . '/view', 'id' => $entity->object_id));
            }
            if(!$entity->magic){
              $entity->magic = Entity::randString(32);
              $entity->save();
            }

            $model = $entity->getObject();
            $dataProvider = Rate::getTo($entity->id);
            $ratesDataProvider = Rate::getTo($entity->id);
            $adsDataProvider = MarketAd::getAds(4, $entity->id);

            $this->render('view', array(
                'model' => $model,
                'entity' => $entity,
                'dataProvider' => $dataProvider,
                'ratesDataProvider' => $ratesDataProvider,
                'adsDataProvider' => $adsDataProvider,
                'is_admin' => $id == Yii::app()->user->getId(),
            ));
        //} else {
        //    $this->redirect(array('site/index'));
        //}
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionMe() {
        $entity = Entity::model()->findByPk(Yii::app()->user->getId());
        if ($entity === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        if ($entity->class == 'Brand') {
            $this->redirect(array('brand/view', 'id' => $entity->object_id));
        }
        if(!$entity->magic){
          $entity->magic = Entity::randString(32);
          $entity->save();
        }

        $model = $entity->getObject();
        $dataProvider = Rate::getTo($entity->id);

        $this->render('me', array(
            'model' => $model,
            'entity' => $entity,
            'dataProvider' => $dataProvider,
        ));
    }


    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionInvited($id, $code) {
        $this->layout = '//layouts/column1';
//buscamos la invitación
        $invitation = Invitation::model()->findByPk($id);
//comprobamos que sea correcta
        if ($invitation->code != $code)
            throw new CHttpException(404, 'Invalid invitation');
//comprobamos que no haya sido usada ya
        if ($invitation->used)
            throw new CHttpException(404, 'Invitation already used');
//comprobamos que no haya caducado
        if ($invitation->created < date('Y-m-d H:i:s', strtotime("-" . Invitation::EXPIRATION . " days")))
            $this->redirect(array('create'));
//mostramos el formulario y creamos el usuario

        $model = new User('register');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model->created_by = $invitation->user_id;
            if ($model->save()) {
                $invitation->used = date('YmdHis');
                $invitation->save();

                $modelLogIn = new LoginForm;
                $modelLogIn->username = $model->username;
                $modelLogIn->password = $model->plain_password;
                $modelLogIn->validate() && $modelLogIn->login();

                ActivityLog::add(Entity::get($model)->id, ActivityLog::SIGNUP);

                Yii::app()->user->setFlash('success', Yii::t('app', 'Welcome to DEMOS'));
                $this->redirect(array(Yii::app()->defaultController));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        if (Yii::app()->user->getId()) {
            Yii::app()->user->setFlash('notice', Yii::t('app', 'You are already signed in, to sign in with another user first Logout (in the top menu)'));
            $this->redirect(array(Yii::app()->defaultController));
        }
        $this->layout = '//layouts/column1';

        $model = new User('register');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save()) {
                $modelLogIn = new LoginForm;
                $modelLogIn->username = $model->username;
                $modelLogIn->password = $model->plain_password;
                $modelLogIn->validate() && $modelLogIn->login();

                //ActivityLog::add(Entity::get($model)->id, ActivityLog::SIGNUP);

                Yii::app()->user->setFlash('success', Yii::t('app', 'Welcome to DEMOS'));
                $this->redirect(array(Yii::app()->defaultController));
            }
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
    public function actionEdit($id) {
        if(!is_numeric($id)){
          throw new CHttpException(404, 'Access denied.');
        }
        if (Yii::app()->user->getId() != $id)
            $this->redirect(array('site/index'));

        $entity = Entity::model()->findByPk($id);
        if ($entity === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        if ($entity->class == 'Brand') {
            $this->redirect(array('brand/view', 'id' => $entity->object_id));
        }

        $model = $entity->getObject();

        $model->setScenario('edit');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model->updated = date('YmdHis');
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        if(!is_numeric($id)){
          throw new CHttpException(404, 'Access denied.');
        }
        if (Yii::app()->user->getId() != $id)
            $this->redirect(array('site/index'));

        $entity = Entity::model()->findByPk($id);
        if ($entity === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        if ($entity->class == 'Brand') {
            $this->redirect(array('brand/view', 'id' => $entity->object_id));
        }

        $model = $entity->getObject();
        $model->setScenario('update');
//		$model->password = null;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model->updated = date('YmdHis');
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
        if (Yii::app()->user->getId() != $id)
            $this->redirect(array('site/index'));

        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->redirect(array('view', 'id' => Yii::app()->user->getId()));
        /* 		$dataProvider=new CActiveDataProvider('User');
          $this->render('index',array(
          'dataProvider'=>$dataProvider,
          )); */
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Request a password recovery
     * If successful, an e-mail will be sent to recover password.
     */
    public function actionRecoveryRequest() {
        $this->layout = '//layouts/column1';
        $model = new RecoveryForm;
        if (isset($_POST['RecoveryForm'])) {
            $model->attributes = $_POST['RecoveryForm'];

            if ($model->validate() && $model->recover()) {
                $this->redirect(array('site/index'));
            }
        }
        $this->render('//recovery/request', array(
            'model' => $model,
        ));
    }

    /**
     * Recovers pasword
     * If successful, a new user password will be sent.
     * @param integer $id the ID of the model to be updated
     * @param string $magic the unique string of the model to allow it to be updated
     */
    public function actionRecovery($id, $magic) {
        $this->layout = '//layouts/column1';
        if (User::recoveryCheck($id, $magic)) {

            $model = $this->loadModel($id);
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                $model->updated = date('YmdHis');
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', Yii::t('app', 'Pasword updated successfully'));
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }

            $model->setScenario('recovery');
            $this->render('update', array(
                'model' => $model,
            ));
        }
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
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

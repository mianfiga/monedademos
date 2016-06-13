<?php

class ContributionController extends Controller {

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
                'actions' => array('index', 'view', 'captcha'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('contact'),
                'users' => array('@'),
            ),
            /* 			array('allow', // allow admin user to perform 'admin' and 'delete' actions
              'actions'=>array('delete'),
              'users'=>array('admin'),
              ), */
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

        $model = $this->loadModel($id);
        $entity = Entity::get($model);
        $dataProvider = new CActiveDataProvider('Rate', array(
                    'criteria' => array(
                        'condition' => 'to_id = ' . $entity->id,
                    ),
                    'sort' => array(
                        'defaultOrder' => 't.updated DESC',
                    ),
                ));

        $this->render('view', array(
            'model' => $this->loadModel($id),
            'entity' => $entity,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionContact($id) {
        $model = new ContributionContactForm;
        $entity = Entity::model()->findByPk(Yii::app()->user->getId());
        $logged = $entity->getObject();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ContributionContactForm'])) {
            $model->attributes = $_POST['ContributionContactForm'];
            if ($model->validate()) {
                $recipent = $this->loadModel($id);
                $headers = "From: contacto@monedademos.es\r\nReply-To: {$logged->email}";
                if (mail($recipent->email, '[Contact from DEMOS] ' . $model->subject, $model->body, $headers)) {
                    Yii::app()->user->setFlash('contact', Yii::t('app', 'Thank you for contacting'));
                    ActivityLog::add($entity->id, ActivityLog::CONTACT, Sid::getSID($recipent). '-Y');
                }
                else {
                    Yii::app()->user->setFlash('error', Yii::t('app', 'E-mail not sent'));
                    ActivityLog::add($entity->id, ActivityLog::CONTACT, Sid::getSID($recipent). '-N');
                }
                $this->refresh();
            }
        }

        $this->render('contact', array(
            'model' => $model,
            'logged' => $logged,
        ));
    }

    public function actionContactEnt($id) {
        $model = new ContributionContactForm;
        $entity = Entity::model()->findByPk(Yii::app()->user->getId());
        $logged = $entity->getObject();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ContributionContactForm'])) {
            $model->attributes = $_POST['ContributionContactForm'];
            if ($model->validate()) {
                if(!is_numeric($id)){
                  throw new CHttpException(404, 'Access denied.');
                }
                $recipent = Entity::model()->findByPk($id); //$this->loadModel($id);
                $headers = "From: contacto@monedademos.es\r\nReply-To: {$logged->email}";
                if (mail($recipent->email, '[Contact from DEMOS] ' . $model->subject, $model->body, $headers)) {
                    Yii::app()->user->setFlash('contact', Yii::t('app', 'Thank you for contacting'));
                    ActivityLog::add($entity->id, ActivityLog::CONTACT, Sid::getSID($recipent). '-Y');
                }
                else {
                    Yii::app()->user->setFlash('error', Yii::t('app', 'E-mail not sent'));
                    ActivityLog::add($entity->id, ActivityLog::CONTACT, Sid::getSID($recipent). '-N');
                }
                $this->refresh();
            }
        }

        $this->render('contact', array(
            'model' => $model,
            'logged' => $logged,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->layout = '//layouts/column1';
        /* 		$dataProvider=new CActiveDataProvider('User');
          $this->render('search',array(
          'dataProvider'=>$dataProvider,
          )); */
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('search', array(
            'model' => $model,
        ));
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

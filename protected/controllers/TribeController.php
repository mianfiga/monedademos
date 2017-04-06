<?php

class TribeController extends Controller {

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
            'postOnly + delete', // we only allow deletion via POST request
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
                'actions' => array('view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('migrationRequest'),
                'users' => array('@'),
            ),
            /*array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create', 'update', 'admin', 'delete'),
                'users' => array('admin'),
            ),*/
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
        $model = $this->loadModel($id);
        $entity = Yii::app()->user->getId()?Entity::model()->findByPk(Yii::app()->user->getId()):null;

        $publicAccountDataProvider = new CActiveDataProvider('Account',
                        array(
                            'criteria' => array(
                                'condition' => 'access = \'public\' AND tribe_id = ' . $model->id,
                            ),
                            'sort' => array(
                                'defaultOrder' => 't.last_action DESC',
                            )
                ));

        $this->render('view', array(
            'model' => $model,
            'record' => Record::getLastRecord($id), //falta isla
            'rule' => Rule::getCurrentRule($model->group_id),
            'entity' => $entity,
            'adsDataProvider' => MarketAd::getAds(null, null, $model->id, 5, 5),
            'newsDataProvider' => MarketAd::getAds(1, Entity::get($model)->id, null, 5, 5),
            'publicAccountDataProvider' => $publicAccountDataProvider,
            'next_rule' => Rule::getDateRule(date(Common::DATETIME_FORMAT, mktime(0, 0, 0, date("n") + 1)), $id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Tribe;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Tribe'])) {
            $model->attributes = $_POST['Tribe'];
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

        if (isset($_POST['Tribe'])) {
            $model->attributes = $_POST['Tribe'];
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
    public function actionMigrationRequest($id) {
      if(!is_numeric($id)){
        $this->redirect(array('site/index'));
        return;
      }
      $model = new TribeMigration;
      $model->to_id = $id;

      $entity = Entity::model()->findByPk(Yii::app()->user->getId());
      if ($entity->tribe_id == $id){
        Yii::app()->user->setFlash('notice', Yii::t('tribe', 'You are already member of this tribe'));
        $this->redirect(array('tribe/view','id'=>$id));
        return;
      }

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      if (isset($_POST['TribeMigration'])) {
        $model->attributes = $_POST['TribeMigration'];
        $model->entity_id = Yii::app()->user->getId();
        $model->status = TribeMigration::STATUS_PENDING;
        $model->added = date('Y-m-d');
        if ($model->save()){
          Yii::app()->user->setFlash('success', Yii::t('tribe', 'Thanks for your interest on becoming part of this tribe. Your request will be attended shortly'));
          $this->redirect(array('view', 'id' => $model->to_id));
        }
      }

      $this->render('migrationRequest', array(
        'model' => $model
      ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Tribe');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Tribe('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Tribe']))
            $model->attributes = $_GET['Tribe'];

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
        if(!is_numeric($id)){
          throw new CHttpException(404, 'Access denied.');
        }
        $model = Tribe::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'tribe-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

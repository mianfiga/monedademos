<?php

class BrandController extends Controller {

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
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'delete'),
                'users' => array('@'),
            ),
            /* 			array('allow', // allow admin user to perform 'admin' and 'delete' actions
              'actions'=>array('admin','delete'),
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
        $model = $this->loadModel($id);
        $entity = Entity::get($model/*,'links'*/);
        $ratesDataProvider = Rate::getTo($entity->id);
        $adsDataProvider = MarketAd::getAds(1, $entity->id);

        $this->render('view', array(
            'model' => $model,
            'entity' => $entity,
            'ratesDataProvider' => $ratesDataProvider,
            'adsDataProvider' => $adsDataProvider,
            'is_admin' => $this->isAdmin($entity),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Brand;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Brand'])) {
            $model->attributes = $_POST['Brand'];
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
        $entity = Entity::get($model/*,'links'*/);
        if (!$this->isAdmin($entity)) {
            $this->redirect(array('site/index'));
        }

        $this->layout = '//layouts/column2';

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Brand'])) {
            $model->attributes = $_POST['Brand'];
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

        //$this->loadModel($id)->delete();

        $model = $this->loadModel($id)->saveAttributes(array('deleted' => Common::datetime()));

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Brand',
                        array(
                            'criteria' => array(
                                'condition' => 'deleted is null',
                            ),
                            'sort' => array(
                                'defaultOrder' => 't.updated DESC',
                            ),
                            'pagination' => array(
                                'pageSize' => 12,
                            ),
                ));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Brand('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Brand']))
            $model->attributes = $_GET['Brand'];

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
        $model = Brand::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'brand-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    protected function isAdmin($entity){
      return isset(Yii::app()->user->logged) && isset(Yii::app()->user->roles[$entity->id]);
    }

}

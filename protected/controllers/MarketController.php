<?php

//Yii::import('application.extensions.EUploadedImage');

class MarketController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
                'actions' => array('index', 'view', 'rss'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'join', 'panel', 'panelView', 'list', 'delete', 'expire'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin'),
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
        if(!is_numeric($id)){
            throw new CHttpException(404, 'Access denied.');
        }
        $entity_id = Yii::app()->user->getId();
        $model = MarketAd::model()->with(
                array(
                    'entities' => array('on' => ($entity_id ? 'entities.id=' . $entity_id : null)),
                    'joined' => array('on' => ($entity_id ? 'joined.entity_id=' . $entity_id : null))
                ))->findByPk($id);

        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionPanel($id) {
        if(!is_numeric($id)){
            throw new CHttpException(404, 'Access denied.');
        }
        $model = $this->loadModel($id); //MarketAd::model()->with('users')->findByPk($id);
        if (isset(Yii::app()->user->roles[$model->created_by])) {
            $criteria = new CDbCriteria;

            $criteria->with = array(
                'marketAds' => array(
                    'together' => true,
                    'condition' => 'marketAds.id=' . $id,
                ),
                'marketJoined' => array(
                    'together' => true,
                    'condition' => 'marketJoined.ad_id=' . $id,
                ),
            );

            $criteria->compare('marketAds.id', $id);

            $dataProvider = new CActiveDataProvider('Entity', array(
                'criteria' => $criteria,
            ));

            Notification::shown($model->created_by, Sid::getSID($model));
            $this->render('panel', array(
                'model' => $model,
                'dataProvider' => $dataProvider,
            ));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('market', 'You can not access to this advertisement control panel'));
            $this->redirect(array('view', 'id' => $id));
        }
    }

    public function actionPanelView($ad_id, $entity_id) {
        if(!is_numeric($ad_id) || !is_numeric($entity_id)){
            throw new CHttpException(404, 'Access denied.');
        }
        $ad = $this->loadModel($ad_id);
        if (!isset(Yii::app()->user->roles[$model->created_by])) {
            $entity = Entity::model()->findByPk($entity_id);
            $joined = MarketJoined::model()->with('entity')->findByPk(array('ad_id' => $ad_id, 'entity_id' => $entity_id));
            $joined->setScenario('panel');

            if (isset($_POST['MarketJoined'])) {
                $joined->attributes = $_POST['MarketJoined'];
                if ($joined->save()) {
                    /*          if($joined->form_comment != '')
                      {
                      $headers  = 'MIME-Version: 1.0' . "\r\n";
                      $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                      $headers .= "From: noreply@instauremoslademocracia.net\r\n";

                      if (mail($user->email,Yii::t('market','[Comment from DEMOS Market]').' '.$ad->title,
                      $this->renderPartial('_joinMail',
                      array('title' => $ad->title,
                      'message' => $joined->form_comment,
                      'ad_id' => $ad_id,
                      'user_id' => $user_id,
                      ),
                      true),$headers))
                      Yii::app()->user->setFlash('success',Yii::t('market','Thank you for contacting'));
                      else
                      Yii::app()->user->setFlash('error',Yii::t('market','E-mail not sent'));
                      } */
                    $this->redirect(array('panel', 'id' => $ad_id));
                }
            }

            Notification::shown($ad->created_by, Sid::getSID($joined));
            $this->render('panelView', array(
                'entity' => $entity,
                'joined' => $joined,
                'ad' => $ad,
                    )
            );
        } else {
            Yii::app()->user->setFlash('error', Yii::t('market', 'You can not access to this advertisement control panel'));
            $this->redirect(array('view', 'id' => $ad_id));
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($id = null) {
        $model = new MarketAd;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $entity = Entity::model()->findByPk(Yii::app()->user->logged);
        $user = $entity->getObject();
        if ($id != null) {
            if (isset(Yii::app()->user->roles[$id])) {
                $model->created_by = $id;
            } else {
                throw new CHttpException(404, 'Access denied.');
            }
        }

        if (isset($_POST['MarketAd'])) {
            $model->attributes = $_POST['MarketAd'];

            if ($model->save()) {
                if ($user->blocked) {
                    ActivityLog::add(Yii::app()->user->logged, ActivityLog::BLOCKED_MARKET_AD, Sid::getSID($model));
                }
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $model->expiration = date('Y-m-d', time() + MarketAd::MAX_EXPIRATION);

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
        if(!is_numeric($id)){
            throw new CHttpException(404, 'Access denied.');
        }
        $model = $this->loadModel($id);
        if (!isset(Yii::app()->user->roles[$model->created_by])) {
            Yii::app()->user->setFlash('error', Yii::t('market', 'You can not update this advertisement'));
            $this->redirect(array('view', 'id' => $id));
            return;
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['MarketAd'])) {
            $model->attributes = $_POST['MarketAd'];
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
    public function actionExpire($id) {
        if(!is_numeric($id)){
            throw new CHttpException(404, 'Access denied.');
        }
        $model = $this->loadModel($id);

        if (!isset(Yii::app()->user->roles[$model->created_by])) {
            Yii::app()->user->setFlash('error', Yii::t('market', 'You can not update this advertisement'));
            $this->redirect(array('view', 'id' => $id));
            return;
        }
        // we only allow deletion via POST request
        $model->expiration = '0000-00-00';
        $model->save();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if(!is_numeric($id)){
            throw new CHttpException(404, 'Access denied.');
        }
        $model = $this->loadModel($id);
        if (!isset(Yii::app()->user->roles[$model->created_by])) {
            Yii::app()->user->setFlash('error', Yii::t('market', 'You can not delete this advertisement'));
            $this->redirect(array('view', 'id' => $id));
            return;
        }

        $model->visible = 0;
        $model->save();
        Yii::app()->user->setFlash('notice', Yii::t('app', 'The advertisement have been moved away'));


        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));

//		else
//			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Join to a Market Ad a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionJoin($id) {
        if(!is_numeric($id)){
            throw new CHttpException(404, 'Access denied.');
        }
        $ad = $this->loadModel($id);
        $entity_id = Yii::app()->user->getId();
        $model = MarketJoined::model()->findByPk(array('ad_id' => $id, 'entity_id' => $entity_id));

        if ($model == null) {
            $model = new MarketJoined;
            $model->ad_id = $id;
            $already_joined = false;
        } else {
            $already_joined = true;
            Yii::app()->user->setFlash('success', Yii::t('market', 'You are already joined, update anything you need.'));
        }

        $model->setScenario('join');

        // uncomment the following code to enable ajax-based validation
        /*
          if(isset($_POST['ajax']) && $_POST['ajax']==='market-joined-join-form')
          {
          echo CActiveForm::validate($model);
          Yii::app()->end();
          }
         */

        if (isset($_POST['MarketJoined'])) {
            $model->attributes = $_POST['MarketJoined'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', Yii::t('market', 'You have successfully joined'));
                    $this->redirect(array('view', 'id' => $ad->id));
                }
            }
        }
        Notification::shown($entity_id, Sid::getSID($model));
        $this->render('join', array('model' => $model, 'ad' => $ad));
    }

    /**
     * Lists all models.
     */
    public function actionList($mode = null, $tribe_id=null) {
        if((!!$mode && !is_numeric($mode)) || (!!$tribe_id && !is_numeric($tribe_id))){
            throw new CHttpException(404, 'Access denied.');
        }
        $this->actionIndex($mode,$tribe_id);
    }

    /**
     * Lists all models.
     */
    public function actionIndex($mode = null, $tribe_id=null) {
        if((!!$mode && !is_numeric($mode)) || (!!$tribe_id && !is_numeric($tribe_id))){
            throw new CHttpException(404, 'Access denied.');
        }
        $entity_id = Yii::app()->user->getId();

        $model = new MarketAd('search');
        $model->unsetAttributes();  // clear any default values
        $tribe= null;
        if ($tribe_id && is_numeric($tribe_id)){
            $tribe = Tribe::model()->findByPk($tribe_id);
        }
        if (isset($_GET['MarketAd']))
            $model->attributes = $_GET['MarketAd'];
        $dataProvider = MarketAd::getAds($model, $mode, $entity_id,$tribe_id);

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'model' => $model,
            'tribe' => $tribe
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new MarketAd('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['MarketAd']))
            $model->attributes = $_GET['MarketAd'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionRss() {
        $entity_id = Yii::app()->user->getId();

        $dataProvider = MarketAd::getAds(null, 3, $entity_id);
    	$dataProvider->setPagination(false);
        $this->renderPartial('rss', array(
            'dataProvider' => $dataProvider,
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
        $model = MarketAd::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'market-ad-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

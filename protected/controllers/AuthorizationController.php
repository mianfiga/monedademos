<?php

class AuthorizationController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('captcha'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user
				'actions'=>array('confirm','update','view'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user
				'actions'=>array('admin','delete'),
				'users'=>array('Fund'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}



	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$accno=Authorization::splitAccountNumber($id);
		$this->render('view',array(
			'model'=>$this->loadModel($accno['entity_id'],$accno['account_id']),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Authorization('new');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Authorization']))
		{
			$model->attributes=$_POST['Authorization'];
			if($model->save())
				$this->redirect(array('view','id' => $model->getAccountNumber()));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$accno=Authorization::splitAccountNumber($id);
		if($accno['entity_id'] != Yii::app()->user->getId())
			throw new CHttpException(403,'You are not authorized to perform this action.');


		$model=$this->loadModel($accno['entity_id'],$accno['account_id']);
		$model->setScenario('update');
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Authorization']))
		{
			$model->attributes=$_POST['Authorization'];
			if($model->save())
            {
                Yii::app()->user->setFlash('success', Yii::t('app', 'Account updated'));
				$this->redirect(array('transaction/index'));
            }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$accno=Authorization::splitAccountNumber($account_number);
			$this->loadModel($accno['entity_id'],$accno['account_id'])->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
/*	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Account');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}*/

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($entity_id, $account_id)
	{
  	if(!is_numeric($entity_id) || !is_numeric($account_id)){
    	throw new CHttpException(404, 'Access denied.');
    }
		$model=Authorization::model()->findByPk(array("entity_id" => $entity_id, "account_id" => $account_id));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');

		return $model;
	}



	/**
	 * Displays the Confirm page
	 */
	public function actionConfirm($sid)
	{
		if(!isset(Yii::app()->session['operations'][$sid]))
			throw new CHttpException(404,'The requested page does not exist.');


		$model= new ConfirmForm;
		$model2= new ConfirmSendForm;
		// if it is ajax validation request
		//if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		//{
		//	echo CActiveForm::validate($model);
		//	Yii::app()->end();
		//}



		if(isset($_POST['ConfirmSendForm']))
		{
			switch(Yii::app()->session['operations'][$sid]['action'])
			{
				case 'transfer':
				case 'charge':
				case 'movement':

					$opmodel = Yii::app()->session['operations'][$sid]['model'];
					if($opmodel->refered_pending != null)
					{
						$this->redirect(array('pending/view',
								'id' => $opmodel->refered_pending,
								'charge_errors' => $opmodel->charge_errors,
								'deposit_errors' => $opmodel->deposit_errors));
						return;
					}

					$pending = new Pending;

					$pending->setAttributes($opmodel->attributes,false);
					$pending->save();

					$url = Yii::app()->session['operations'][$sid]['url'];
					$array= Yii::app()->session['operations'];
					unset($array[$sid]);
					Yii::app()->session['operations'] = $array;

					$this->redirect(array('pending/view',
							'id' => $pending->id,
							'charge_errors' => $opmodel->charge_errors,
							'deposit_errors' => $opmodel->deposit_errors));
			}
		}
		elseif(isset($_POST['ConfirmForm']))
		{
			$model->attributes=$_POST['ConfirmForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate())
			{
				switch(Yii::app()->session['operations'][$sid]['action'])
				{
					case 'transfer':
					case 'charge':
					case 'movement':

						$opmodel = Yii::app()->session['operations'][$sid]['model'];

                        $url = Yii::app()->session['operations'][$sid]['url'];

						if($opmodel->save()){
                            $array= Yii::app()->session['operations'];
                            unset($array[$sid]);
                            Yii::app()->session['operations'] = $array;
                        }

						$this->redirect(array($url,
								'id' => $opmodel->id,
								'charge_errors' => $opmodel->charge_errors,
								'deposit_errors' => $opmodel->deposit_errors));
				}
			}
		}
		$model->sid=$sid;
		$model2->sid=$sid;
		// display the confirm form
		$this->render('confirm',array('model'=>$model, 'model2'=>$model2));
	}

}

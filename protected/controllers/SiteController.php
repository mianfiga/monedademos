<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionPre() {
        Site::mobileCheck();
        Site::languageCheck();

        if (Yii::app()->session['mobile'])
            $this->redirect(array('transaction/index'));
        elseif (Yii::app()->user->isGuest)
            $this->redirect(array('site/index'));
        else
            $this->redirect(array('transaction/index'));
    }

    public function actionMenu() {
        Site::mobileCheck();
        Site::languageCheck();

        if (isset(Yii::app()->request->cookies['mobile']) && Yii::app()->request->cookies['mobile']->value == 1)
            $this->render('menu');
        else
            $this->redirect(array('site/index'));
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        Site::mobileCheck();
        Site::languageCheck();

        $entity_id = null;
        if (Yii::app()->user) {
            $entity_id = Yii::app()->user->getId();
        }
        $dataProviderMarketAd = MarketAd::getAds(null, 3, $entity_id, null, 10);

        $dataProviderMarketAd->setPagination(false);

        if (Yii::app()->session['mobile'])
            Yii::app()->setTheme('mobile');
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('index', array(
//            'record' => Record::getLastRecord(),
//            'rule' => Rule::getCurrentRule(),
//            'next_rule' => Rule::getDateRule(date(Common::DATETIME_FORMAT, mktime(0, 0, 0, date("n") + 1))),
            'dataProviderMarketAd' => $dataProviderMarketAd,
            'tribes' => Tribe::Model()->findAll()
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {

        if (Yii::app()->user->getId()) {
            Yii::app()->user->setFlash('notice', Yii::t('app', 'You are already signed in, to sign in with another user first Logout (in the top menu)'));
            $this->redirect(array(Yii::app()->defaultController));
        }
        $model = new LoginForm;
        $modelRegister = new User('register');
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['User'])) {
            $modelRegister->attributes = $_POST['User'];

            if ($modelRegister->save()) {
                $model->username = $modelRegister->username;
                $model->password = $modelRegister->plain_password;
                $model->validate() && $model->login();
                Yii::app()->user->setFlash('success', Yii::t('app', 'Welcome to DEMOS'));
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }

        // collect user input data

        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model, 'modelRegister' => $modelRegister));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        ActivityLog::add(Yii::app()->user->id, ActivityLog::LOGOUT);
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     *
     */
    public function actionMobile() {
        if (isset($_POST['MobileForm'])) {
            $model = new MobileForm;
            $model->attributes = $_POST['MobileForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
//				unset(Yii::app()->request->cookies['mobile']);
//				Yii::app()->session['mobile'] = $model->mobile;
                $cookie = new CHttpCookie('mobile', ($model->mobile == 1 ? 1 : 0));
                $cookie->expire = time() + 60 * 60 * 24 * 180;
                Yii::app()->request->cookies['mobile'] = $cookie;
                $this->redirect($model->url);
            }
        }
    }

    /**
     *
     */
    public function actionLanguage() {
        if (isset($_POST['LanguageForm'])) {
            $model = new LanguageForm;
            $model->attributes = $_POST['LanguageForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
//				unset(Yii::app()->request->cookies['mobile']);
//				Yii::app()->session['mobile'] = $model->mobile;
                $cookie = new CHttpCookie('language', $model->language);
                $cookie->expire = time() + 60 * 60 * 24 * 180;
                Yii::app()->request->cookies['language'] = $cookie;
                if (Yii::app()->user->getId() != null) {
                    $entity = Entity::model()->findByPk(Yii::app()->user->getId());
                    $entity->getObject()->saveAttributes(array('culture' => $model->language));
                }
                $this->redirect($model->url);
            }
        }
    }

    /**
     *
     */
    public function actionRoles() {
        if (isset($_POST['RolesForm'])) {
            $model = new RolesForm;
            $model->attributes = $_POST['RolesForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
                if (Yii::app()->user->getId() != null) {
                    Yii::app()->user->setId($model->role);
                    Yii::app()->user->setName(Yii::app()->user->roles[$model->role]);
                }
                $this->redirect($model->url);
            }
        }
    }

}

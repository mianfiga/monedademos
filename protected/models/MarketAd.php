<?php

Yii::import('application.extensions.EUploadedImage');

/**
 * This is the model class for table "{{market_ad}}".
 *
 * The followings are the available columns in table '{{market_ad}}':
 * @property string $id
 * @property string $title
 * @property string $class
 * @property string $type
 * @property string $summary
 * @property string $price
 * @property string $description
 * @property string $image
 * @property string $mailmode
 * @property integer $visible
 * @property string $expiration
 * @property string $zip
 * @property string $created_by
 * @property string $added
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property Entity $createdBy
 * @property User[] $rbuUsers
 */
class MarketAd extends MarketAdBase {

    const MAX_EXPIRATION = 2592000; //3600*24*30
    const EXPIRATION_PRENOTIFICATION_DAYS = 5;
    const THUMB_PREFIX = 'thumb_';

    public $form_price;
    public $form_image;
    public $expired;

    public static function classOptions() {
        return array('product' => Yii::t('market', 'Product'), 'service' => Yii::t('market', 'Service'));
    }

    public static function typeOptions() {
        return array('offer' => Yii::t('market', 'Offer'), 'requirement' => Yii::t('market', 'Request'));
    }

    public static function mailmodeOptions() {
        return array('instantly' => Yii::t('market', 'Instantly'),
            'daily' => Yii::t('market', 'Daily'),
            'exired' => Yii::t('market', 'On expiration'));
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MarketAdBase the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('class, type, price, mailmode, expiration', 'required'),
//      array('expiration', 'required'),
            array('visible', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 127),
            array('class', 'length', 'max' => 7),
            array('type', 'length', 'max' => 11),
            array('form_price', 'length', 'max' => 20),
            array('form_price', 'match', 'pattern' => '/^\d+(\.\d{2})?$/', 'message' => '{attribute} is invalid. Use: # or #.##'),
            array('form_image', 'file', 'types' => 'jpg, gif, png', 'allowEmpty' => true),
//			array('mailmode', 'length', 'max'=>9),
            array('summary, description, zip', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('title, class, type, summary, price, description, zip', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'createdBy' => array(self::BELONGS_TO, 'Entity', 'created_by'),
            'joined' => array(self::HAS_MANY, 'MarketJoined', 'ad_id'/* ,'with'=>'users' */),
            'entities' => array(self::MANY_MANY, 'Entity', '{{market_joined}}(ad_id, entity_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => Yii::t('market', 'Title'),
            'class' => Yii::t('market', 'Class'),
            'type' => Yii::t('market', 'Type'),
            'summary' => Yii::t('market', 'Summary'),
            'form_price' => Yii::t('market', 'Price'),
            'description' => Yii::t('market', 'Description'),
            'form_image' => Yii::t('market', 'Image'),
            'mailmode' => Yii::t('market', 'E-mail mode'),
            'visible' => Yii::t('market', 'Public'),
            'expiration' => Yii::t('market', 'Expiration'),
            'zip' => Yii::t('app', 'Zip code'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('title', $this->title, true);
        $criteria->compare('class', $this->class, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('summary', $this->summary, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('expiration', $this->expiration, true);
        $criteria->compare('zip', $this->zip, true);



        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {

            //check expiration date

            $max_date = date('Y-m-d', self::MAX_EXPIRATION + date('U'));
            if ($this->expiration > $max_date) {
                $this->addError('expiration', 'Max expiration is ' . (self::MAX_EXPIRATION / 86400) . ' days from today (' . $max_date . '). Update in the future to get extra time.');
                return false;
            }
            if ($this->isNewRecord) {
                $this->added = date('YmdHis');
                $this->created_by = Yii::app()->user->getId();
                //registramos la transacción
            }

            $this->form_image = EUploadedImage::getInstance($this, 'form_image');

            if ($this->form_image != null) {
                $this->form_image->maxWidth = 500;
                $this->form_image->maxHeight = 400;

                $this->form_image->thumb = array(
                    'maxWidth' => 150,
                    'maxHeight' => 120,
//				    'dir' => Yii::getPathOfAlias('webroot.images.market'),
                    'prefix' => MarketAd::THUMB_PREFIX,
                );

                $ext = substr($this->form_image, strrpos($this->form_image, '.'));
                $img_name = uniqid();
                $this->form_image->saveAs(Yii::getPathOfAlias('webroot.images.market') . '/' . $img_name . $ext);
                $this->image = $img_name . $ext;
            }

            $this->updated = date('YmdHis');

            if ($this->form_price != null) {
                $this->price = Transaction::amountUserToSystem($this->form_price);
            }
            return true;
        }
        else
            return false;
    }

    protected function afterSave() {
        parent::afterSave();
        $this->expired = $this->expiration < date('Y-m-d');
        if ($this->expired) {
            $notif_data = array('{title}' => $this->title);
            Notification::addNotification(Notification::MARKET_AD_EXPIRED, $this->created_by, Sid::getSID($this), $notif_data);
        } else {
            Notification::removeNotification(Notification::MARKET_AD_EXPIRED, $this->created_by, Sid::getSID($this));
        }
    }

    protected function afterFind() {
        parent::afterFind();
        $this->form_price = Transaction::amountSystemToUserNo($this->price);
        $this->expired = $this->expiration < date('Y-m-d');
    }

}

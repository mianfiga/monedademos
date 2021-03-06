<?php

Yii::import('application.extensions.EUploadedImage');
/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property string $id
 * @property string $username
 * @property string $salt
 * @property string $password
 * @property string $name
 * @property string $surname
 * @property string $identification
 * @property string $ability_title
 * @property string $ability_text
 * @property string $email
 * @property string $contact
 * @property string $zip
 * @property string $created
 * @property string $created_by
 * @property string $last_login
 * @property string $last_action
 * @property string $updated
 * @property string $blocked
 * @property string $deleted
 *
 * The followings are the available model relations:
 * @property Account[] $rbuAccounts
 * @property Transaction[] $transactions
 */
class User extends UserBase {

    const ABILITY_INVITE = 'invite';

    public $plain_password;
    public $password2;
    public $identification_method;
    public $identification_number;
    public $verifyCode;
    protected $_isNew = false;
    public $conditions;
    public $form_image;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserBase the static model class
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
            array('name, surname', 'required', 'on' => 'register, update'),
            array('zip, country', 'required', 'on' => 'register, edit'),
            array('username', 'required', 'on' => 'register'),
            array('username', 'length', 'max' => 16, 'on' => 'register'),
            array('username', 'unique', 'on' => 'register'),
            array('username', 'match', 'pattern' => '/^.\w+$/', 'message' => '{attribute} can contain alphanumeric characters only', 'on' => 'register'),
            array('email', 'required', 'on' => 'register, update'),
            array('plain_password, password2', 'required', 'on' => 'register, recovery'),
            array('plain_password, password2', 'safe', 'on' => 'update, recovery'),
            array('plain_password', 'length', 'max' => 128, 'on' => 'register, update, recovery'),
            array('password2', 'compare', 'compareAttribute' => 'plain_password', 'on' => 'register, update, recovery'),
            array('name', 'length', 'max' => 127, 'on' => 'register, update'),
            array('email', 'email', 'on' => 'register, update'),
            array('email', 'unique', 'on' => 'register, update'),
            array('surname', 'length', 'max' => 255, 'on' => 'register, upddate'),
            array('contribution_title, email', 'length', 'max' => 255, 'on' => 'register, edit'),
            array('zip', 'length', 'max' => 16, 'on' => 'register, edit'),
            array('birthday', 'safe', 'on' => 'register,update'),
            array('identification_method, identification_number', 'safe', 'on' => 'update'),
            array('contact, contribution_text', 'safe', 'on' => 'register,edit'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'register,update, recovery'),
            array('conditions', 'match', 'pattern' => '/^1$/', 'on' => 'register'),
            array('form_image', 'file', 'types' => 'jpg, gif, png', 'allowEmpty' => true, 'on' => 'register,update'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('username, name, surname, contribution_title, contribution_text, zip, created, blocked, deleted', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.

        return array(
            'accounts' => array(self::MANY_MANY, 'Account', '{{authorization}}(user_id, account_id)'),
            'chargePendings' => array(self::HAS_MANY, 'Pending', 'charge_user'),
            'depositPendings' => array(self::HAS_MANY, 'Pending', 'deposit_user'),
            'exemption' => array(self::BELONGS_TO, 'Exemption', 'exemption_id'),
            'createdBy' => array(self::BELONGS_TO, 'Entity', 'created_by'),
            //'hasCreated' => array(self::HAS_MANY, 'User', 'created_by'),
            'entity' => array(self::HAS_ONE, 'Entity', 'object_id',
                'on' => '`entity`.`class`=\'' . get_class(self::model()) . '\''
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'salt' => 'Salt',
            'plain_password' => Yii::t('app', 'Password'),
            'password2' => Yii::t('app', 'Confirm password'),
            'name' => Yii::t('app', 'Name'),
            'surname' => Yii::t('app', 'Surname'),
            'identification' => Yii::t('app', 'Identification'),
            'contribution_title' => Yii::t('app', 'Contribution in short'),
            'contribution_text' => Yii::t('app', 'Detailed contribution'),
            'email' => Yii::t('app', 'Email'),
            'contact' => Yii::t('app', 'Contact'),
            'birthday' => Yii::t('app', 'Birthday'),
            'zip' => Yii::t('app', 'Zip code'),
            'country' => Yii::t('app', 'Country'),
            'verifyCode' => Yii::t('app', 'Verification Code'),
            'created' => Yii::t('app', 'Created'),
            'blocked' => Yii::t('app', 'Blocked'),
            'deleted' => Yii::t('app', 'Deleted'),
            'conditions' => Yii::t('app', 'Conditions'),
            'form_image' => Yii::t('market', 'Image')
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


        $criteria->compare('contribution_title', $this->contribution_title, true);
        $criteria->compare('contribution_text', $this->contribution_text, true);
        $criteria->compare('deleted', null);
        $criteria->compare('zip', $this->zip, true);

        $criteria->order = 'updated DESC, id DESC'; // last_login DESC,

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 30,
            ),
        ));
    }

    public function validatePassword($password) {
        return self::hashPassword($password, $this->salt) === $this->password;
    }

    public static function hashPassword($password, $salt) {
        return md5($salt . $password);
    }

    public static function identificationList() {
        return array(
            'DNI' => 'DNI',
            'PASSPORT' => Yii::t('app', 'Passport'),
            'OTHER' => Yii::t('app', 'OTHER (specify)'),
            'NONE' => Yii::t('app', 'NONE (not recommended)')
        );
    }

    public static function randString($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $size = strlen($chars);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }

        return $str;
    }

    public static function randCode($length = 1) {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        $size = strlen($chars);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }

        return $str;
    }

    protected function afterFind() {
        $ids = explode(': ', $this->identification);
        $this->identification_method = $ids[0];
        unset($ids[0]);
        $this->identification_number = implode(': ', $ids);
        if (strlen($this->contribution_text) < strlen($this->contribution_title)) {
            $aux = $this->contribution_text;
            $this->contribution_text = $this->contribution_title;
            $this->contribution_title = $aux;
        }
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->_isNew = true;
                $date = Common::datetime();
                $this->salt = md5(self::randString(64));
                $this->password = self::hashPassword($this->plain_password, $this->salt);
                $this->created = $date;
                $this->updated = $date;
                $this->last_action = $date;
                if (Yii::app()->user->getId() != null && $this->created_by == null) {
                    $this->created_by = Yii::app()->user->getId();
                }
            } else {
                $this->_isNew = false;
                if ($this->plain_password != null) {
                    $this->salt = md5(self::randString(64));
                    $this->password = self::hashPassword($this->plain_password, $this->salt);
                }
            }

            $this->form_image = EUploadedImage::getInstance($this, 'form_image');

            if ($this->form_image != null) {
                $this->form_image->maxWidth = 500;
                $this->form_image->maxHeight = 400;

                $this->form_image->thumb = array(
                    'maxWidth' => 150,
                    'maxHeight' => 120,
//				    'dir' => Yii::getPathOfAlias('webroot.images.market'),
                    'prefix' => Brand::THUMB_PREFIX,
                );

                $ext = substr($this->form_image, strrpos($this->form_image, '.'));
                $img_name = uniqid();
                $this->form_image->saveAs(Yii::getPathOfAlias('webroot.images.users') . '/' . $img_name . $ext);
                $this->image = $img_name . $ext;
            }

            $this->identification = $this->identification_method . ': ' . $this->identification_number;
            return true;
        } else
            return false;
    }

    protected function afterSave() {
        if ($this->_isNew) {
            $entity = new Entity;
            $entity->class = get_class($this);
            $entity->object_id = $this->id;
            if ($this->created_by) {
                $entity->tribe_id = $this->createdBy->tribe_id;
            } else {
                $entity->tribe_id = Tribe::DEFAULT_TRIBE;
            }
            $entity->save();

            $date = Common::datetime();
            $this->_isNew = false;
            $acc = new Account;
            $acc->title = Yii::t('app', 'Personal account');
            $acc->added = $date;
            $acc->last_action = $date;
            $acc->class = Account::CLASS_USER;
            $acc->tribe_id = $entity->tribe_id;
            $acc->save();

            $auth = new Authorization;
            $auth->entity_id = $entity->id;
            $auth->account_id = $acc->id;
            $auth->code = self::randCode();
            $auth->title = $acc->title;
            $auth->salt = $this->salt;
            $auth->password = $this->password;
            $auth->added = $date;
            $auth->save();

            $acc->addSalary();

            //Records Update
            $user_count = User::model()->with('entity')->count('entity.tribe_id = \'' . $entity->tribe_id . '\' AND deleted is NULL');
            $accounts = Account::model()->findAll('tribe_id = \'' . $entity->tribe_id . '\'');
            $total_amount = 0;
            foreach ($accounts as $account) {
                $total_amount += $account->credit;
            }

            Record::updateRecord(array('total_amount' => $total_amount, 'user_count' => $user_count), $entity->tribe_id);

            ActivityLog::add($entity->id, ActivityLog::SIGNUP);
        }
        parent::afterSave();
    }

    /*  protected function delete()
      {
      //Marcar como eliminada, y vaciar información del perfil y contraseñas
      //marcar como eliminada la cuenta del usuario $acc->delete() (por desarrollar será ahí donde se retiren los fondos asignador tras la creación del usuario);
      //eliminar la autorización
      } */

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getCulture() {
        return $this->culture;
    }

    static public function recoveryCheck($id, $magic) {
        if ($model = self::model()->findByPk($id)) {
            return $model->magic == $magic; //falta añadir limite de fecha
        }
    }

}

<?php

Yii::import('application.extensions.EUploadedImage');

/**
 * This is the model class for table "{{brand}}".
 *
 * The followings are the available columns in table '{{brand}}':
 * @property string $id
 * @property string $name
 * @property string $summary
 * @property string $description
 * @property string $image
 * @property string $created_by
 * @property string $added
 * @property string $updated
 * @property string $deleted
 *
 * The followings are the available model relations:
 * @property Entity $createdBy
 */
class Brand extends BrandBase {

    const THUMB_PREFIX = 'thumb_';

    public $form_image;
    public $contribution_title;
    public $contribution_text;
    protected $_isNew = false;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BrandBase the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{brand}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'length', 'max' => 127),
            array('form_image', 'file', 'types' => 'jpg, gif, png', 'allowEmpty' => true),
            array('created_by', 'length', 'max' => 10),
            array('name, email, summary', 'required'),
            array('email', 'email'),
            array('summary, description, added, updated, deleted', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('name, summary, description', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'summary' => Yii::t('market', 'Summary'),
            'description' => Yii::t('market', 'Description'),
            'form_image' => Yii::t('market', 'Image'),
            'contribution_title' => Yii::t('app', 'Name'),
            'contribution_text' => Yii::t('app', 'Summary'),
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('summary', $this->summary, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('created_by', $this->created_by, true);
        $criteria->compare('added', $this->added, true);
        $criteria->compare('updated', $this->updated, true);
        $criteria->compare('deleted', $this->deleted, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {

            if ($this->isNewRecord) {
                $this->_isNew = true;
                $this->added = Common::datetime();
                $this->created_by = Yii::app()->user->getId();
            } else {
                $this->_isNew = false;
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
                $this->form_image->saveAs(Yii::getPathOfAlias('webroot.images.brands') . '/' . $img_name . $ext);
                $this->image = $img_name . $ext;
            }

            $this->updated = Common::datetime();
            return true;
        }
        else
            return false;
    }

    protected function afterSave() {
        if ($this->_isNew) {
            $logged_entity = Yii::app()->user->logged;
            $entity = new Entity;
            $entity->class = get_class($this);
            $entity->object_id = $this->id;
            $entity->save();

            $date = Common::datetime();
            $this->_isNew = false;
            $acc = new Account;
            $acc->title = $this->name;
            $acc->added = $date;
            $acc->last_action = $date;
            $acc->class = Account::CLASS_GROUP;
            $acc->save();

            $auth = new Authorization;
            $auth->entity_id = $entity->id;
            $auth->account_id = $acc->id;
            $auth->code = User::randCode();
            $auth->title = $acc->title;
            $auth->added = $date;
            $auth->save();

            $auth2 = new Authorization;
            $auth2->entity_id = $logged_entity;
            $auth2->account_id = $acc->id;
            $auth2->code = User::randCode();
            $auth2->title = $acc->title;
            $auth2->class = Authorization::CLASS_AUTHORIZED;
            $auth2->added = $date;
            $auth2->save();

            $role = new Role;
            $role->actor_id = $logged_entity;
            $role->part_id = $entity->id;
            $role->save();
            
            $roles= Yii::app()->user->roles;
            $roles[$entity->id] = $this->name;
            Yii::app()->user->roles = $roles;
        }
        parent::afterSave();
    }

    protected function afterFind() {
        parent::afterFind();

        $this->contribution_title = $this->name;
        $this->contribution_text = $this->summary;
    }
    
    public function getSurname(){
        return '';
    }

}

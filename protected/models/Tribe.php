<?php

/**
 * This is the model class for table "{{tribe}}".
 *
 * The followings are the available columns in table '{{tribe}}':
 * @property string $id
 * @property string $nickname
 * @property string $name
 * @property string $email
 * @property string $summary
 * @property string $description
 * @property string $image
 * @property string $last_action
 * @property string $group_id
 * @property string $created_by
 * @property string $added
 * @property string $updated
 * @property string $deleted
 *
 * The followings are the available model relations:
 * @property MarketAd[] $rbuMarketAds
 * @property Entity $createdBy
 * @property TribeGroup $group
 */
class Tribe extends TribeBase
{
    const DEFAULT_TRIBE = 1;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tribe the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tribe}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nickname, email', 'required'),
			array('nickname, name', 'length', 'max'=>127),
			array('email', 'length', 'max'=>255),
			array('image', 'length', 'max'=>254),
			array('group_id, created_by', 'length', 'max'=>10),
			array('summary, description, last_action, added, updated, deleted', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nickname, name, email, summary, description, image, last_action, group_id, created_by, added, updated, deleted', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'marketAds' => array(self::MANY_MANY, 'MarketAd', '{{market_ad_tribe}}(tribe_id, ad_id)'),
			'createdBy' => array(self::BELONGS_TO, 'Entity', 'created_by'),
			'group' => array(self::BELONGS_TO, 'TribeGroup', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nickname' => 'Nickname',
			'name' => 'Name',
			'email' => 'Email',
			'summary' => 'Summary',
			'description' => 'Description',
			'image' => 'Image',
			'last_action' => 'Last Action',
			'group_id' => 'Group',
			'created_by' => 'Created By',
			'added' => 'Added',
			'updated' => 'Updated',
			'deleted' => 'Deleted',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('last_action',$this->last_action,true);
		$criteria->compare('group_id',$this->group_id,true);
		$criteria->compare('created_by',$this->created_by,true);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('deleted',$this->deleted,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    protected function beforeSave() {
        if (parent::beforeSave()) {

            if ($this->isNewRecord) {
                $this->_isNew = true;
                $this->added = Common::datetime();
                $this->created_by = Yii::app()->user->logged;
                $creator = Entity::model()->findByPk($this->created_by);
                $this->culture = $creator->culture;
                
                $group = new TribeGroup;
                $group->save();
                $this->group_id = $group->id;
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
            $entity->tribe_id = $this->id;
            $entity->save();

            $date = Common::datetime();
            $this->_isNew = false;
            
            //Fund account
            $acc = new Account;
            $acc->title = $this->name . ' FUND';
            $acc->added = $date;
            $acc->last_action = $date;
            $acc->class = Account::CLASS_FUND;
            $acc->save();

            $auth = new Authorization;
            $auth->entity_id = $entity->id;
            $auth->account_id = $acc->id;
            $auth->code = User::randCode();
            $auth->title = $acc->title;
            $auth->added = $date;
            $auth->save();
            
            //System Account
            $acc2 = new Account;
            $acc2->title = $this->name . ' SYSTEM';
            $acc2->added = $date;
            $acc2->last_action = $date;
            $acc2->class = Account::CLASS_SYSTEM;
            $acc2->save();

            $auth2 = new Authorization;
            $auth2->entity_id = $entity->id;
            $auth2->account_id = $acc2->id;
            $auth2->code = User::randCode();
            $auth2->title = $acc->title;
            $auth2->added = $date;
            $auth2->save();

            $auth3 = new Authorization;
            $auth3->entity_id = $logged_entity;
            $auth3->account_id = $acc2->id;
            $auth3->code = User::randCode();
            $auth3->title = $acc->title;
            $auth3->class = Authorization::CLASS_AUTHORIZED;
            $auth3->added = $date;
            $auth3->save();

            $role = new Role;
            $role->actor_id = $logged_entity;
            $role->part_id = $entity->id;
            $role->save();

            $roles = Yii::app()->user->roles;
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

    public function getSurname() {
        return '';
    }
}
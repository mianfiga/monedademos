<?php

/**
 * This is the model class for table "{{authorization}}".
 *
 * The followings are the available columns in table '{{authorization}}':
 * @property string $user_id
 * @property string $account_id
 * @property string $code
 * @property string $class
 * @property string $salt
 * @property string $password
 * @property integer $wrong_pass_count
 * @property string $added
 * @property string $blocked
 * @property string $deleted
 */

class Authorization extends AuthorizationBase
{

		const CLASS_HOLDER   = 'holder'; //1;
		const CLASS_AUTHORIZED = 'authorized'; //2;
		const CLASS_CONSULTANT = 'consultant'; //3;

	public $user_password;
	public $plain_password;
	public $password2;
	public $username;
	public $_isNew=false;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserBase the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
/*			array('user_id, account_id', 'required'),
			array('user_id, account_id, class', 'length', 'max'=>10),*/
			array('username', 'required', 'on'=>'new'),
			array('username', 'length', 'max'=>128, 'on'=>'new'),
			array('username', 'exist', 'attributeName'=> 'username', 'className'=>'User'), //'criteria'=>'que no haya sido borrado'
			array('title', 'length', 'max'=>127),
			array('user_password, plain_password, password2', 'safe', 'on'=>'update'),
			array('user_password, plain_password', 'length', 'max'=>128, 'on'=>'update'),
			array('password2', 'compare', 'compareAttribute'=>'plain_password'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('title', 'safe', 'on'=>'search'),
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
            'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
            'entity' => array(self::BELONGS_TO, 'Entity', 'entity_id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'username' => Yii::t('app','Username'),
			'class' => Yii::t('app','Class'),
			'user_password' => Yii::t('app','User Password ({username})',array('{username}' => Yii::app()->user->name)),
			'plain_password' => Yii::t('app','Account Pin/Password'),
			'password2' => Yii::t('app','Confirm account Pin/Password'),
			'title' => Yii::t('app','Title'),
		);
	}

	public static function splitAccountNumber($account_number)
	{
		$a = explode('.', $account_number);
		if(count($a)<3)
			return null;
		$return = array();
		$return['entity_id']=$a[0];
		$return['account_id']=$a[1];
		$return['code']=$a[2];
		return $return;
	}
  
  public static function formAccountNumber($entity_id, $account_id, $code='*')
  {
    return $entity_id.'.'.$account_id.'.'.$code;
  }

	public function getAccountNumber($show_code = true)
	{
		//EntityId.AccountId.Code
        if ($show_code){
            return self::formAccountNumber($this->entity_id, $this->account_id, $this->code);    
        }
		else{
            return self::formAccountNumber($this->entity_id, $this->account_id);    
        }
	}

    public static function getByEntity($entity_id, $condition='')
	{
		return Authorization::model()->findAllByAttributes(array('entity_id' => $entity_id), $condition);//falta filtrar si está eliminado,bloqueado,etc.
	}
    
    public static function getByAccount($account_id, $condition='')
	{
		return Authorization::model()->findAllByAttributes(array('account_id' => $account_id), $condition);//falta filtrar si está eliminado,bloqueado,etc.
	}

	public static function getAccountList($entity_id, $condition ='')
	{
		$auths = self::getByEntity($entity_id, $condition);

		$return=array();
		foreach($auths as $auth)
		{
			$return[$auth->getAccountNumber()] = $auth->title.' - '.$auth->getAccountNumber();
		}

		return $return;
	}
    
    public static function getAuthorization($account_number)
    {
        if (is_string($account_number))
        {
            $account_number = Authorization::splitAccountNumber($account_number);
        }
        return Authorization::model()->FindByPk(array('entity_id'=>$account_number['entity_id'],'account_id'=> $account_number['account_id']));
    }

    public static function isValidAccountNumber($account_number)
	{
		$a = Authorization::splitAccountNumber($account_number);
		if($a==null)
		{
			return false;
		}
		$auth = self::getAuthorization($a);
		if ($auth===null || $auth->code != $a['code'])
			return false;
		return $auth;
	}

	protected function beforeSave()
	{
		if(parent::beforeSave()) //falta comprobar que si no tiene superpermisos que la cuenta sea la del usuario logeado
		{
			$this->wrong_pass_count = 0;

			if($this->isNewRecord)
			{
				$this->_isNew = true;

				$date = date('YmdHis');
				$this->added = $date;

				if($this->getScenario()=='new')
				{

				}
			}
			else
			{
				$this->_isNew = false;

				if ($this->user_password!=null || $this->plain_password!=null)
				{   //falta comprobar si la entidad es un usuario
					if($this->getScenario()=='update' &&
							Yii::app()->user->getId() == $this->entity_id &&
							User::model()->findByPk($this->entity->object_id)->validatePassword($this->user_password) )
					{
						$this->salt = md5(self::randString(64));
						$this->password = self::hashPassword($this->plain_password,$this->salt);
					}
					else
					{
						Yii::app()->user->setFlash('error','Pin/Password have not been updated');
						return false;
					}
				}
			}
			return true;
		}
		else
			return false;
	}

	protected function afterSave()
	{
		if($this->_isNew)
		{
		}
		parent::afterSave();
	}

	public static function hashPassword($password,$salt)
	{
		return User::hashPassword($password,$salt);
	}
	public static function randString($length)
	{
		return User::randString($length);
	}


}

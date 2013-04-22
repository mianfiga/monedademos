<?php

/**
 * This is the model class for table "{{market_joined}}".
 *
 * The followings are the available columns in table '{{market_joined}}':
 * @property string $ad_id
 * @property string $user_id
 * @property string $comment
 * @property integer $show_mail
 * @property string $status
 * @property string $added
 * @property string $updated
 */
class MarketJoined extends MarketJoinedBase
{

	public $form_comment;
	public $email_comment;

  protected $_isNew = false;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MarketJoinedBase the static model class
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
//			array('ad_id, user_id, status', 'required'),
//			array('show_mail', 'numerical', 'integerOnly'=>true),
//			array('ad_id', 'length', 'max'=>20),
//			array('user_id, status', 'length', 'max'=>10),
			array('form_comment, email_comment, status', 'safe', 'on' => 'panel'),
			array('form_comment, show_mail', 'safe', 'on' => 'join'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ad_id, user_id, comment, show_mail, status, added, updated', 'safe', 'on'=>'search'),
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
			'entity' => array(self::BELONGS_TO, 'Entity', 'entity_id'),
			'ad' => array(self::BELONGS_TO, 'MarketAd', 'ad_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
//			'ad_id' => 'Ad',
//			'user_id' => 'User',
			'form_comment' => Yii::t('market','Comment'),
			'show_mail' => Yii::t('market','Show Mail'),
			'status' => Yii::t('market','Status'),
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

		$criteria->compare('ad_id',$this->ad_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('show_mail',$this->show_mail);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
        $this->_isNew = true;
				$this->added = date('YmdHis');
				$this->user_id = Yii::app()->user->getId();
				$this->status = 'pending';
			}
			$this->updated = date('YmdHis');

			if($this->getScenario()=='join')
			{
				if($this->form_comment!='')
					$this->comment .= '<br/>['. date('d-m-Y H:i:s') .']: '.CHtml::encode($this->form_comment);
			}
			elseif($this->getScenario()=='panel')
			{
				if($this->form_comment!='')
					$this->comment .= '<br/><b>['. date('d-m-Y H:i:s') .']</b>: '. CHtml::encode($this->form_comment);
				
			}

			return true;
		}
		else
			return false;
	}

  
    
	protected function afterSave()
	{
    parent::afterSave();
    
    if($this->_isNew)
    {
      $notif_data = array($this->user_id => array('{user_id}' => $this->user_id,
                                                  '{user_name}' => $this->user->name,
                                                  '{added}' => date('YmdHis')));
      
      Notification::addNotification(Notification::MARKET_JOINED, $this->ad->created_by, Notification::getSID($this->ad), $notif_data);
    }
    
    if($this->form_comment != '')
    {
      $notif_data = array('{comment}' => $this->form_comment,
                                '{added}' => date('YmdHis'));
      if($this->ad->created_by == Yii::app()->user->getId())
        Notification::addNotification(Notification::MARKET_CREATOR_COMM, $this->user_id, Notification::getSID($this), $notif_data);
      else
        Notification::addNotification(Notification::MARKET_JOINED_COMM, $this->ad->created_by, Notification::getSID($this), $notif_data);
    }
  }

	public static function statusList()
	{
		return array(
									'pending' => Yii::t('market','Pending'),
									'accepted' => Yii::t('market','Accepted'),
									'substitute' => Yii::t('market','Substitute'),
									'rejected' => Yii::t('market','Rejected'),
							);
	}

}

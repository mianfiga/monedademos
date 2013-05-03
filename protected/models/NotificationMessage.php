<?php

/**
 * This is the model class for table "{{notification_user}}".
 *
 * The followings are the available columns in table '{{notification_user}}':
 * @property string $notification_id
 * @property string $user_id
 * @property string $sid
 * @property string $data
 * @property string $added
 * @property string $sent
 * @property string $read
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property NotificationConfiguration $notification
 * @property User $user
 */
class NotificationMessage extends NotificationMessageBase {

    private $_object = null;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return NotificationUserBase the static model class
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
			array('entity_id, notification_id', 'required'),
			array('entity_id', 'length', 'max'=>11),
			array('notification_id', 'length', 'max'=>10),
			array('sid', 'length', 'max'=>127),
			array('data, added, sent, read, updated, shown', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('entity_id, notification_id, sid, data, added, sent, read, updated, shown', 'safe', 'on'=>'search'),
		);
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'entity' => array(self::BELONGS_TO, 'Entity', 'entity_id'),
            'notification' => array(self::BELONGS_TO, 'Notification', 'notification_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'notification_id' => 'Notification',
            'user_id' => 'User',
            'sid' => 'Sid',
            'data' => 'Data',
            'added' => 'Added',
            'sent' => 'Sent',
            'read' => 'Read',
            'updated' => 'Updated',
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

        $criteria->compare('notification_id', $this->notification_id, true);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('sid', $this->sid, true);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('added', $this->added, true);
        $criteria->compare('sent', $this->sent, true);
        $criteria->compare('read', $this->read, true);
        $criteria->compare('updated', $this->updated, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->added = date('YmdHis');
            }
            if ($this->getScenario() != 'notify')
                $this->updated = date('YmdHis');
            return true;
        }
        return false;
    }

    public function viewedNotification() {
        
    }

    public function emailNotification() {
        ;
    }

    public function getObject() {
        if ($this->_object === null)
            return $this->_object = Sid::getObject($this->sid);
        else
            return $this->_object;
    }

    public function getUrl($absolute = false) {
        $object = $this->getObject();

        switch ($this->notification_id) {
            case Notification::PAYMENT:
            case Notification::CHARGE:
            case Notification::SALARY:
            case Notification::TAX:
                return Yii::app()->createAbsoluteUrl('transaction/view', array('id' => $object->id));
            case Notification::PENDING:
                return Yii::app()->createAbsoluteUrl('pending/view', array('id' => $object->id));
            case Notification::ADVICE:
                return Yii::app()->createAbsoluteUrl('transaction/list');
            case Notification::MARKET_JOINED:
                return Yii::app()->createAbsoluteUrl('market/Panel', array('id' => $object->id));
            case Notification::MARKET_JOINED_COMM:
                return Yii::app()->createAbsoluteUrl('market/PanelView', array('ad_id' => $object->ad_id, 'entity_id' => $object->entity_id));
            case Notification::MARKET_AD_EXPIRATION:
                return Yii::app()->createAbsoluteUrl('market/update', array('id' => $object->id, '#' => 'expiration'));
            case Notification::MARKET_AD_EXPIRED:
                return Yii::app()->createAbsoluteUrl('market/update', array('id' => $object->id, '#' => 'expiration'));
            case Notification::MARKET_CREATOR_COMM:
                return Yii::app()->createAbsoluteUrl('market/join', array('id' => $object->ad_id));
            case Notification::MARKET_AD_STATUS:
                return Yii::app()->createAbsoluteUrl('market/view', array('id' => $object->ad_id));
            case Notification::CONTRIBUTION_COMM:
                return null;
        }
    }

    public function get_message_array() {
        $array = json_decode($this->data, true);

        if (($n = count($array)) > 1) {
            $aux = array();
            foreach ($array as $sub) {
                if (isset($sub['{added}']) && $sub['{added}'] > date('YmdHis', strtotime($this->shown)))
                    $new = 'N_';
                else
                    $new = '';
                foreach ($sub as $key => $value) {
                    $aux[$new . $key] = (isset($aux[$new . $key]) ? $aux[$new . $key] . ', ' . $sub[$key] : $sub[$key]);
                    $aux['N_' . $key] = (isset($aux['N_' . $key]) ? $aux['N_' . $key] : '');
                    $aux[$key] = (isset($aux[$key]) ? $aux[$key] : '');
                }
            }
            $array = array();
            $array[0] = $aux;
        }
        $aux = array_shift($array);

        array_unshift($aux, $n);

        return $aux;
    }

    public function get_message_extra() {
        $object = $this->getObject();

        switch ($this->notification_id) {
            case Notification::MARKET_JOINED:
                return array('{ad_title}' => $object->title);
            case Notification::MARKET_JOINED_COMM:
                return array('{ad_title}' => $object->ad->title, '{user_name}' => $object->entity->getName());
            case Notification::MARKET_CREATOR_COMM:
                return array('{ad_title}' => $object->ad->title, '{user_name}' => $object->entity->getName());
            default:
                return array();
        }
    }

    public function message() {
        $mess = array_merge($this->get_message_array(), $this->get_message_extra());
        return Yii::t('notification', $this->notification->message, $mess);
    }

    public function subject() {
        $mess = array_merge($this->get_message_array(), $this->get_message_extra());
        return Yii::t('notification', $this->notification->subject, $mess);
    }

    public function read() {
        $this->saveAttributes(array('read' => date('YmdHis')));
    }

}

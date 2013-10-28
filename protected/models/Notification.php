<?php

/**
 * This is the model class for table "{{notification}}".
 *
 * The followings are the available columns in table '{{notification}}':
 * @property string $id
 * @property string $title
 * @property string $message
 * @property string $subject
 * @property string $view
 *
 * The followings are the available model relations:
 * @property User[] $rbuUsers
 */
class Notification extends NotificationBase {

    const EXPIRATION = 432000; //5 días(86400×5)
    const PAYMENT = 1;
    const CHARGE = 2;
    const SALARY = 3;
    const TAX = 4;
    const PENDING = 5;
    const ADVICE = 6;
    const MARKET_JOINED = 7;
    const MARKET_JOINED_COMM = 8;
    const MARKET_AD_EXPIRATION = 9;
    const MARKET_AD_EXPIRED = 10;
    const MARKET_CREATOR_COMM = 11;
    const MARKET_AD_STATUS = 12;
    const CONTRIBUTION_COMM = 13;
    const SYSTEM = 14;
    const SELF_PAYMENT = 15;
    const SELF_CHARGE = 16;
    const RECIPROCITY_LACK = 17;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return NotificationBase the static model class
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
            array('title, subject, view', 'length', 'max' => 127),
            array('message', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, title, message, subject, view', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'entities' => array(self::MANY_MANY, 'Entity', '{{notification_configuration}}(notification_id, entity_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'message' => 'Message',
            'subject' => 'Subject',
            'view' => 'View',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('subject', $this->subject, true);
        $criteria->compare('view', $this->view, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function addNotification($notification_id, $entity_id, $SID, $data) {
        $notif_mess = NotificationMessage::model()->findByPk(array('notification_id' => $notification_id, 'entity_id' => $entity_id, 'sid' => $SID));
        if ($notif_mess === null) {
            $notif_mess = new NotificationMessage;
            $notif_mess->notification_id = $notification_id;
            $notif_mess->entity_id = $entity_id;
            $notif_mess->sid = $SID;
        }
        $pre_data = ($notif_mess->data != null ? json_decode($notif_mess->data, true) : array());
        reset($data);
        if (is_numeric(key($data))) {
            $pre_data = array_merge($pre_data, $data);
        } else {
            array_unshift($pre_data, $data);
        }
        $notif_mess->data = json_encode($pre_data);
        $notif_mess->save();
    }

    public static function removeNotification($notification_id, $entity_id, $SID, $data = null) {
        $notif_mess = NotificationMessage::model()->findByPk(array('notification_id' => $notification_id, 'entity_id' => $entity_id, 'sid' => $SID));
        if ($notif_mess === null)
            return true;

        if ($data !== null) {
            $pre_data = ($notif_mess->data != null ? json_decode($notif_mess->data) : array());

            foreach ($data as $key => $value) {
                unset($pre_data[$key]);
            }
            $notif_mess->data = json_encode($pre_data);
            return $notif_mess->save();
        }

        return $notif_mess->delete();
    }

    public static function shown($entity_id, $SID) {
        $notifs = NotificationMessage::model()->findAllByAttributes(array('entity_id' => $entity_id, 'sid' => $SID));
        foreach ($notifs as $notif) {
            $notif->saveAttributes(array('shown' => date('YmdHis')));
        }
    }

    public static function notify() {

        $notifs = NotificationMessage::model()->findAll(
                array('condition' => 't.`sent` < t.`updated` AND t.`read` < t.`updated` AND t.`shown` < t.`updated`',
                    'with' => array('notification' => array('together' => true),
                        'entity' => array('together' => true),
//                                  'configuration' => array('together' => true),
                    ),
                ));

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= "From: noreply@monedademos.es\r\n";

        foreach ($notifs as $notif) {
            $configuration = NotificationConfiguration::model()->find("notification_id=$notif->notification_id AND (entity_id=$notif->entity_id OR entity_id=1) ORDER BY entity_id DESC");

            $udate = strtotime($notif->sent); //date_timestamp_get(DateTime::createFromFormat('Y-m-d H:i:s',$notif->sent));

            if ($configuration->mailmode == NotificationConfiguration::MAILMODE_INSTANTLY
                    || ($configuration->mailmode == NotificationConfiguration::MAILMODE_DAILY &&
                    ($udate + 86400) < time() )) {
                Yii::app()->setLanguage($notif->entity->getCulture());
                if (mail($notif->entity->getEmail(), $notif->subject(), CController::renderInternal(Yii::getPathOfAlias('application.views') . ($notif->notification->view != '' ? $notif->notification->view : '/notification/_mail') . '.php', array('data' => $notif), true), $headers)) {
                    $notif->sent = date('YmdHis');
                }
            }

            $notif->setScenario('notify');
            $notif->save();
        }
    }

}

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sid
 *
 * @author mafg
 */
class Sid {

    public static function getSID($object) {
        switch (get_class($object)) {
            case 'Transaction':
                return 'tr-' . $object->id;
            case 'Pending':
                return 'pe-' . $object->id;
            case 'MarketAd':
                return 'ad-' . $object->id;
            case 'MarketJoined':
                return 'jo-' . $object->ad_id . '-' . $object->entity_id;
        }
    }

    public static function getObject($sid) {
        if ($sid == null)
            return null;

        $data = explode('-', $sid);
        switch ($data[0]) {
            case 'tr':
                return Transaction::model()->findByPk($data[1]);
            case 'pe':
                return Pending::model()->findByPk($data[1]);
            case 'ad':
                return MarketAd::model()->findByPk($data[1]);
            case 'jo':
                return MarketJoined::model()->findByPk(array('ad_id' => $data[1], 'entity_id' => $data[2]));
        }
    }
    
    public static function getUrl($sid, $absolute = false) {
        
        if ($sid == null)
            return null;

        $data = explode('-', $sid);
        switch ($data[0]) {
            case 'tr':
                return Yii::app()->createAbsoluteUrl('transaction/view',
                        array('id' => $data[1]));
            case 'pe':
                return Yii::app()->createAbsoluteUrl('pending/view',
                        array('id' => $data[1]));
            case 'ad':
                return Yii::app()->createAbsoluteUrl('market/view',
                        array('id' => $data[1]));
            case 'jo':
                //Falta, depende del usuario logeado se visita una cosa u otra
        }
    }
    
    public static function getName($sid){
        if ($sid == null)
            return null;

        $data = explode('-', $sid);
        switch ($data[0]) {
            case 'tr':
                return Yii::t('app', 'transaction');
            case 'pe':
                return Yii::t('app', 'pending transaction');
            case 'ad':
                return Yii::t('app', 'advertisement');
            case 'jo':
                //Falta, depende del usuario logeado se visita una cosa u otra
        }
        
    }

}

?>

<?php

/**
 * Common stuff is centralized here, specific of backend
 *
 * @author mafg
 */
class Common {

    const DATE_FORMAT = 'Y-m-d';
    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    static public function date() {
        return date(self::DATE_FORMAT);
    }

    static public function datetime() {
        return date(self::DATETIME_FORMAT);
    }

}

?>
<?php
//based on http://www.yiiframework.com/wiki/38/how-to-use-nested-db-transactions-mysql-5-postgresql/
//but modified to make possible several nested transaction calls keeping (actually) one single transaction

class NestedPDO extends PDO {

    // The current transaction level.
    protected $transLevel = 0;

    public function beginTransaction() {
        if($this->transLevel == 0) {
            parent::beginTransaction();
        } //else: Already in transaction we do nothing

        $this->transLevel++;
    }

    public function commit() {
        $this->transLevel--;
        if($this->transLevel == 0) {
            parent::commit();
        } //else: Already in transaction we do nothing
    }

    public function rollBack() {
        $this->transLevel = 0;
        // one rollback rolls back everything
        parent::rollBack();
    }
}

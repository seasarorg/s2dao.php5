<?php

/**
 * @author nowel
 */
class S2Dao_ObjectResultSetHandler implements S2Dao_ResultSetHandler {

    private $beanClass;

    public function S2Dao_ObjectResultSetHandler($beanClass = null) {
        $this->beanClass = $beanClass;
    }

    public function handle($rs) {
        if($this->beanClass === null){
            return $rs->fetchAll(PDO::FETCH_OBJ);
        } else {
            return $rs->fetchAll(PDO::FETCH_INTO, $this->beanClass);
        }
    }
}

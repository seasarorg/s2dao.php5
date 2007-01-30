<?php

/**
 * @author nowel
 */
interface CdTxManager {
    public function requiredInsert();
    public function requiresNewInsert();
    public function mandatoryInsert();
    public function getAll();
}

?>

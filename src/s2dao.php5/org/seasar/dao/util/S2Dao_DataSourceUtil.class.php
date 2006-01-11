<?php

/**
 * @author nowel
 */
final class S2Dao_DataSourceUtil {

    private function __construct() {
    }

    public static function getConnection(S2Container_DataSource $dataSource) {
        try {
            return $dataSource->getConnection();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
?>

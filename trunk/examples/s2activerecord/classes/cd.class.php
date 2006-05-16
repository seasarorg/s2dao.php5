<?php
class cd extends S2ActiveRecord {
    const TABLE = 'CD';
    
    protected function __find_all(PDOStatement $stmt){
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ret = array();
        foreach($rows as $row){
            $row = array_change_key_case($row, CASE_LOWER);
            $ret[] = array($row["id"], $row["title"]);
        }
        return $ret;
    }
    
    private function __find_hoge($foo){
        die;
    }
}
?>
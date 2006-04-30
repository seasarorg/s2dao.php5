<?php

/**
 * @author nowel
 */
class S2Dao_MySQL extends S2Dao_Standard {

    public function getSuffix() {
        return '_mysql';
    }
    
    public function getIdentitySelectString() {
        return 'SELECT LAST_INSERT_ID()';
    }
    
    public function getTableSql(){
        return 'SHOW TABLES';
    }
    
    public function getTableInfoSql(){
        return 'SELECT * FROM ' . self::BIND_TABLE . ' LIMIT 0';
    }
    
    public function getProcedureNamesSql(){
        return 'SELECT * FROM mysql.proc';
    }
    
    public function analysProcedureParams($procedureParam){
        $inType = array();
        $outType = array();
        $inoutType = array();
        preg_match_all('/(IN\s+?(\S+)\s+?(\S+),?)/i', $procedureParam, $match, PREG_SET_ORDER);
        foreach($match as $m){
            $in = explode(',', $m[3]);
            $inType[] = array(
                            'name' => $m[2],
                            'type' => trim($in[0])
                        );
        }
        preg_match_all('/(OUT\s+?(\S+)\s+?(\S+),?)/i', $procedureParam, $match, PREG_SET_ORDER);
        foreach($match as $m){
            $out = explode(',', $m[3]);
            $outType[] = array(
                            'name' => $m[2],
                            'type' => trim($in[0])
                        );
        }
        
        preg_match_all('/(^(IN|OUT)?(\S+)\s+?(\S+),?)/i', $procedureParam, $match, PREG_SET_ORDER);
        foreach($match as $m){
            if(!preg_match('/IN|OUT/i', $m[3])){
                $inoutType[] = array(
                                'name' => null,
                                'type' => null,
                            );
            }
        }
        return array('inType' => $inType,
                     'outType' => $outType,
                     'inoutType' => $inoutType);
    }
}
?>

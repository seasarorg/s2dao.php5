<?php

/**
 * @author nowel
 */
class Oracle extends S2Dao_Standard {

    public function getSuffix() {
        return "_oci";
    }
    
    protected function createAutoSelectFromClause(S2Dao_BeanMetaData $beanMetaData) {
        $buf = "";
        $whereBuf = "";
        $buf .= "FROM ";
        $myTableName = $beanMetaData->getTableName();
        $buf .= $myTableName;
        for ($i = 0; $i < $beanMetaData->getRelationPropertyTypeSize(); ++$i) {
            $rpt = $beanMetaData->getRelationPropertyType($i);
            $bmd = $rpt->getBeanMetaData();
            $buf .= ", ";
            $buf .= $bmd->getTableName();
            $buf .= " ";
            $yourAliasName = $rpt->getPropertyName();
            $buf .= $yourAliasName;
            for ($j = 0; $j < $rpt->getKeySize(); ++$j) {
                $whereBuf .= $myTableName;
                $whereBuf .= ".";
                $whereBuf .= $rpt->getMyKey($j);
                $whereBuf .= " = ";
                $whereBuf .= $yourAliasName;
                $whereBuf .= ".";
                $whereBuf .= $rpt->getYourKey($j);
                $whereBuf .= "(+)";
                $whereBuf .= " AND ";
            }
        }
        if (strlen($whereBuf) > 0) {
            $whereBuf = preg_replace("/ AND $/", "", $whereBuf);
            $buf .= " WHERE ";
            $buf .= $whereBuf;
        }
        return $buf;
    }
    
    public function getSequenceNextValString($sequenceName) {
        return "SELECT " . $sequenceName . ".nextval FROM dual";
    }
    
    public function getTableSql(){
        return "SELECT table_name FROM user_tables";
    }
    
    public String getTableInfoSql(){
        return null;
    }
}

?>

<?php

/**
 * @author nowel
 */
class S2Dao_Standard implements S2Dao_Dbms {

    private $autoSelectFromClauseCache_ = null;

    public function __construct(){
        $this->autoSelectFromClauseCache_ = new S2Dao_HashMap();
    }

    public function getSuffix() {
        return "";
    }

    public function getAutoSelectSql(S2Dao_BeanMetaData $beanMetaData) {
        $buf = "";
        $buf .= $beanMetaData->getAutoSelectList();
        $buf .= " ";

        $beanClass = new ReflectionClass($beanMetaData->getBeanClass());
        $beanName = $beanClass->getName();
        $fromClause = (string)$this->autoSelectFromClauseCache_->get($beanName);
        if ($fromClause == null) {
            $fromClause = $this->createAutoSelectFromClause($beanMetaData);
            $this->autoSelectFromClauseCache_->put($beanName, $fromClause);
        }
        $buf .= $fromClause;
        return $buf;
    }

    protected function createAutoSelectFromClause(S2Dao_BeanMetaData $beanMetaData) {
        $buf = "";
        $buf .= "FROM ";

        $myTableName = $beanMetaData->getTableName();
        $buf .= $myTableName;

        for ($i = 0; $i < $beanMetaData->getRelationPropertyTypeSize(); ++$i) {
            $rpt = $beanMetaData->getRelationPropertyType($i);
            $bmd = $rpt->getBeanMetaData();

            $buf .= " LEFT OUTER JOIN ";
            $buf .= $bmd->getTableName();
            $buf .= " ";

            $yourAliasName = $rpt->getPropertyName();
            $buf .= $yourAliasName;
            $buf .= " ON ";

            for ($j = 0; $j < $rpt->getKeySize(); ++$j) {
                $buf .= $myTableName;
                $buf .= ".";
                $buf .= $rpt->getMyKey($j);
                $buf .= " = ";
                $buf .= $yourAliasName;
                $buf .= ".";
                $buf .= $rpt->getYourKey($j);
                $buf .= " AND ";
            }
            //buf.setLength(buf.length() - 5);
        }

        return $buf;
    }

    public function getIdentitySelectString() {
        return null;
    }

    public function getSequenceNextValString($sequenceName) {
        return null;
    }
}
?>
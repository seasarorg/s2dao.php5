<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id$
//
/**
 * @author nowel
 */
class S2Dao_Standard implements S2Dao_Dbms {

    const baseSqlPattern = '/^.*?(select)/i';
    private $autoSelectFromClauseCache_ = null;

    public function __construct(){
        $this->autoSelectFromClauseCache_ = new S2Dao_HashMap();
    }

    public function getSuffix() {
        return '';
    }

    public function getAutoSelectSql(S2Dao_BeanMetaData $beanMetaData) {
        $buf = '';
        $buf .= $beanMetaData->getAutoSelectList();
        $buf .= ' ';

        $beanName = $beanMetaData->getBeanClass()->getName();
        $fromClause = $this->autoSelectFromClauseCache_->get($beanName);
        if ($fromClause == null) {
            $fromClause = $this->createAutoSelectFromClause($beanMetaData);
            $this->autoSelectFromClauseCache_->put($beanName, $fromClause);
        }
        $buf .= $fromClause;
        return $buf;
    }

    protected function createAutoSelectFromClause(S2Dao_BeanMetaData $beanMetaData) {
        $buf = 'FROM ';

        $myTableName = $beanMetaData->getTableName();
        $buf .= $myTableName;

        for ($i = 0; $i < $beanMetaData->getRelationPropertyTypeSize(); ++$i) {
            $rpt = $beanMetaData->getRelationPropertyType($i);
            $bmd = $rpt->getBeanMetaData();

            $buf .= ' LEFT OUTER JOIN ';
            $buf .= $bmd->getTableName();
            $buf .= ' ';

            $yourAliasName = $rpt->getPropertyName();
            $buf .= $yourAliasName;
            $buf .= ' ON ';

            for ($j = 0; $j < $rpt->getKeySize(); ++$j) {
                $buf .= $myTableName;
                $buf .= '.';
                $buf .= $rpt->getMyKey($j);
                $buf .= ' = ';
                $buf .= $yourAliasName;
                $buf .= '.';
                $buf .= $rpt->getYourKey($j);
                $buf .= ' AND ';
            }
            $buf = preg_replace('/( AND )$/', '', $buf);
        }

        return $buf;
    }
    
    public function isSelfGenerate() {
        return true;
    }
    
    public function getBaseSql($sql) {
        if (preg_match(self::baseSqlPattern, $sql, $m)) {
            return $m[1];
        }
        return $sql;
    }

    public function getIdentitySelectString() {
        return null;
    }

    public function getSequenceNextValString($sequenceName) {
        return null;
    }
    
    public function getTableSql(){
        return null;
    }
    
    public function getTableInfoSql(){
        return null;
    }

    public function getPrimaryKeySql(){
        return null;
    }
    
    public function getProcedureNamesSql(){
        return null;
    }
    
    public function getProcedureInfoSql(){
        return null;
    }
    
    public function getLimitOffsetSql(){
        return null;
    }
    
    public function usableLimitOffsetQuery() {
        return false;
    }
    
}
?>

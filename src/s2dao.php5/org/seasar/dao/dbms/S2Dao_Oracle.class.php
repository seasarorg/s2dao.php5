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
class S2Dao_Oracle extends S2Dao_Standard {

    public function getSuffix() {
        return '_oracle';
    }
    
    protected function createAutoSelectFromClause(S2Dao_BeanMetaData $beanMetaData) {
        $buf = '';
        $whereBuf = '';
        $buf .= 'FROM ';
        $myTableName = $beanMetaData->getTableName();
        $buf .= $myTableName;
        for ($i = 0; $i < $beanMetaData->getRelationPropertyTypeSize(); ++$i) {
            $rpt = $beanMetaData->getRelationPropertyType($i);
            $bmd = $rpt->getBeanMetaData();
            $buf .= ', ';
            $buf .= $bmd->getTableName();
            $buf .= ' ';
            $yourAliasName = $rpt->getPropertyName();
            $buf .= $yourAliasName;
            for ($j = 0; $j < $rpt->getKeySize(); ++$j) {
                $whereBuf .= $myTableName;
                $whereBuf .= '.';
                $whereBuf .= $rpt->getMyKey($j);
                $whereBuf .= ' = ';
                $whereBuf .= $yourAliasName;
                $whereBuf .= '.';
                $whereBuf .= $rpt->getYourKey($j);
                $whereBuf .= '(+)';
                $whereBuf .= ' AND ';
            }
        }
        if (0 < strlen($whereBuf)) {
            $whereBuf = preg_replace('/( AND )$/', '', $whereBuf);
            $buf .= ' WHERE ';
            $buf .= $whereBuf;
        }
        return $buf;
    }
    
    public function getSequenceNextValString($sequenceName) {
        return 'SELECT ' . $sequenceName . '.nextval FROM dual';
    }
    
    public function getTableSql(){
        return 'SELECT table_name FROM user_tables';
    }
    
    public function getTableInfoSql(){
        return 'SELECT u.column_name, u.data_type, u.nullable, ' .
               'u.data_precision, u.char_col_decl_length ' .
               'FROM user_tab_columns u ' .
               'WHERE u.table_name = ' . self::BIND_TABLE;
    }

    public function getPrimaryKeySql(){
        return 'SELECT t.constraint_type ' .
               'FROM user_constraints t, user_cons_columns c ' . 
               'WHERE t.table_name = ' . self::BIND_TABLE .
               ' AND t.table_name = c.table_name' .
               ' AND t.owner = c.owner' .
               ' AND c.column_name = ' . self::BIND_COLUMN .
               ' AND t.constraint_name = c.constraint_name' .
               ' AND t.constraint_type = \'P\'';
    }
}

?>

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
// $Id: $
//
/**
 * @author nowel
 * @package org.seasar.s2dao.handler
 */
class S2Dao_BeanJsonMetaDataResultSetHandler extends S2Dao_BeanArrayMetaDataResultSetHandler {

    public function __construct(S2Dao_BeanMetaData $beanMetaData,
                                S2Dao_Dbms $dbms,
                                array $relationPropertyHandlers) {
        parent::__construct($beanMetaData, $dbms, $relationPropertyHandlers);
        if (!extension_loaded('json')){
            throw new Exception('resultset json reqiored php_json extension');
        }
    }

    public function handle(PDOStatement $rs){
        // json_decode requires php_json extension
        return json_encode(parent::handle($rs));
    }
}
?>

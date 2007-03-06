<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
 * @package org.seasar.s2dao.interceptors
 */
class S2DaoInterceptor extends S2Container_AbstractInterceptor {

    private $daoMetaDataFactory;

    public function __construct(S2Dao_DaoMetaDataFactory $daoMetaDataFactory) {
        $this->daoMetaDataFactory = $daoMetaDataFactory;
    }

    public function invoke(S2Container_MethodInvocation $invocation){
        try {
            $method = $invocation->getMethod();
            if (!S2Container_MethodUtil::isAbstract($method)) {
                return $invocation->proceed();
            }

            $targetClass = $this->getTargetClass($invocation);
            $dmd = $this->daoMetaDataFactory->getDaoMetaData($targetClass);
            $cmd = $dmd->getSqlCommand($method->getName());
            $ret = $cmd->execute($invocation->getArguments());
            return $ret;
        } catch (Exception $e){
            throw $e;
        }
    }
}
?>

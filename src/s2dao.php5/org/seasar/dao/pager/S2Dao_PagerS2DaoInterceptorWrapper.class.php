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
// | Authors: yonekawa                                                    |
// +----------------------------------------------------------------------+
// $Id: $
//
/**
 * S2DaoInterceptorをラップして結果をペ−ジングするインターセプタ
 *
 * S2Daoを利用して取得した結果セットを、検索条件DTOによってペ−ジングします。
 * @author yonekawa
 * @package org.seasar.s2dao.pager
 */
class S2Dao_PagerS2DaoInterceptorWrapper extends S2DaoInterceptor
{
    private $useLimitOffsetQuery = false;

    private $daoMetaDataFactory_;
 
    public function __construct(S2Dao_DaoMetaDataFactory $daoMetaDataFactory) {
        parent::__construct($daoMetaDataFactory);
        $this->daoMetaDataFactory_ = $daoMetaDataFactory;
    }

    /**
     * @param boolean ペ−ジングにSQLのLimit句を使用するかどうかの真偽値
     */
    public function setUseLimitOffsetQuery($useLimitOffsetQuery)
    {
        $this->useLimitOffsetQuery = $useLimitOffsetQuery;
    }

    /**
     * useLimitOffsetQueryプロパティの値によって、ペ−ジング処理を振り分ける
     * @param $invocation
     */
    public function invoke(S2Container_MethodInvocation $invocation) 
    {
        if ($this->useLimitOffsetQuery) {
            $result = $this->invokePagerWithLimitOffsetQuery($invocation);
        } else {
            $result = $this->invokePagerWithoutLimitOffsetQuery($invocation);
        }

        return $result;
    }

    /**
     * Limit,Offset句を使用してページャを実行する
     * @param $invocation
     */
    private function invokePagerWithLimitOffsetQuery(S2Container_MethodInvocation $invocation)
    {
        try{
            $method = $invocation->getMethod();
            if (!S2Container_MethodUtil::isAbstract($method)) {
                return $invocation->proceed();
            }

            $targetClass = $this->getTargetClass($invocation);
            $dmd = $this->daoMetaDataFactory_->getDaoMetaData($targetClass);
            $cmd = $dmd->getSqlCommand($method->getName());

            $args = $invocation->getArguments();

            if (count($args) >= 1) {
                if ($args[0] instanceof S2Dao_PagerCondition) {
                    $cmd = new S2Dao_SelectDynamicCommandLimitOffsetWrapper($cmd);
                }
            }
            $ret = $cmd->execute($invocation->getArguments());

            return $ret;
        } catch (Exception $e){
            throw $e;
        }
    }

    /**
     * Limit,Offset句を使用せずにページャを実行する
     * @param $invocation
     */
    private function invokePagerWithoutLimitOffsetQuery(S2Container_MethodInvocation $invocation)
    {
        try{
            $args = $invocation->getArguments();
            $result = parent::invoke($invocation);
            
            $noArgs = (count($args) < 1);
            $notArray = !(is_array($result));
            $notArrayList = !($result instanceof S2Dao_ArrayList);
            $notJsonOrYaml = !(is_string($result));

            if ($noArgs) {
                return $result;
            }
            if ($notArray && $notArrayList && $notJsonOrYaml) {
                return $result;
            }

            $argsIsPagerCondition = ($args[0] instanceof S2Dao_PagerCondition);

            if ($argsIsPagerCondition) {
                $condition = $args[0];
                $wrapper = S2Dao_PagerResultSetWrapperFactory::create($invocation);
                return $wrapper->filter($result, $condition);
            }
        } catch(Exception $e) {
            throw $e;
        } 
        
        return $result;
    }
}

?>

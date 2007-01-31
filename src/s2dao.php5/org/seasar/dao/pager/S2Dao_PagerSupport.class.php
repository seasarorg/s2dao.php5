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
// | Authors: yonekawa                                                    |
// +----------------------------------------------------------------------+
// $Id: $
//
/**
 * DTOのセッションへの格納をサポートします。
 * @author yonekawa
 * @package org.seasar.s2dao.pager
 */
class S2Dao_PagerSupport
{
    /** 最大取得件数  */
    private $limit;

    /** 検索条件クラス名  */
    private $pagerConditionClass;
    
    /** 検索条件オブジェクトのセッション中の名前 */
    private $pagerConditionName = 'ORG_SEASAR_PAGER_CONDITION';

    /**
     * コンストラクタ
     * セッションを開始して、指定された最大取得件数,検索条件DTOクラス名,
     * 検索条件DTOのセッション中の名前で新しいPagerSupportインスタンスを生成します
     * @param limit 最大取得件数
     * @param pagerConditionClass 検索条件DTOクラス名
     * @param pagerConditionName 検索条件DTOのセッション中の名前
     */
    public function __construct($limit = NULL, 
                                $pagerConditionClass = NULL,
                                $pagerConditionName = NULL)
    {
        if (!headers_sent() && session_id() == '') {
            session_start();
        }
        
        $this->limit = ($limit !== NULL) ? $limit : S2Dao_PagerCondition::NONE_LIMIT;
        $this->pagerConditionClass = ($pagerConditionClass !== NULL) 
                                    ? $pagerConditionClass 
                                    : 'S2Dao_DefaultPagerCondition';
        $this->pagerConditionName = ($pagerConditionName !== NULL) 
                                    ? $pagerConditionName 
                                    : 'ORG_SEASAR_PAGER_CONDITION';
    }

    /** 
     * セッション中の検索条件DTOのoffsetを更新します
     * @param offset 更新Offset
     */
    public function updateOffset($offset)
    {
        $pagerCondition = $this->getPagerCondition();
        if (empty($offset)) {
            $offset = 0;
        }
        $pagerCondition->setOffset($offset);
        $_SESSION[$this->pagerConditionName] = $pagerCondition;
    }
    
    /**
     * セッション中の検索条件DTOを取得します。
     * 検索条件DTOが存在しない場合、新規に検索条件DTOを生成します。
     * @return 検索条件DTO
     */
    public function getPagerCondition()
    {
        $dto = null;
        if (! is_null($_SESSION) && array_key_exists($this->pagerConditionName, $_SESSION)) {
            $dto = $_SESSION[$this->pagerConditionName];
        }
        
        if (empty($dto)) {
            $refClass = new ReflectionClass($this->pagerConditionClass);
            $dto = $refClass->newInstance();
            $dto->setLimit($this->limit);

            $_SESSION[$this->pagerConditionName] = $dto;
        }
        return $dto;
    }

}

?>
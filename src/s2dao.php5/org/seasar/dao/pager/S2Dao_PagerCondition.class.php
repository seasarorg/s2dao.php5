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
// $Id$
//
/**
 * ページャ条件オブジェクトのインターフェイス
 * @author yonekawa
 */
interface S2Dao_PagerCondition
{
    /** limitのデフォルト値  */
    const NONE_LIMIT = -1;
    
    /**
     * 検索結果の総件数を取得します。
     * @return 総件数
     */
    public function getCount();
    
    /**
     * 検索結果の総件数をセットします。
     * @param count 総件数
     */
    public function setCount($count);
    
    /**
     * 検索結果から一度に取得する最大件数を取得します。
     * @return 最大件数
     */
    public function getLimit();
    
    /**
     * 検索結果から一度に取得する最大件数をセットします。
     * @param limit 最大件数
     */
    public function setLimit($limit);
    
    /**
     * 検索結果の取得開始位置ををセットします。
     * @param offset 取得開始位置
     */
    public function setOffset($offset);

    /**
     * 検索結果の取得開始位置をを取得します。
     * @return 取得開始位置
     */
    public function getOffset();
}

?>
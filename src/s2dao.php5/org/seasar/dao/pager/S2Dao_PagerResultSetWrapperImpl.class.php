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
 * @author yonekawa
 */
class S2Dao_PagerResultSetWrapperImpl implements S2Dao_PagerResultSetWrapper
{
    /**
     * S2Daoの結果をDTOの条件でラップして返します
     * @param result S2Daoの結果
     * @param condition DTO
     */
    public function filter($result, $condition)
    {
        $returnArray = false;
        $retValue = new S2Dao_ArrayList();
        if(!($result instanceof S2Dao_ArrayList)){
            $result = new S2Dao_ArrayList(new ArrayObject($result));
            $returnArray = true;
        }
        
        $condition->setCount($result->size());
     
        $limit = $condition->getOffset() + $condition->getLimit();
        $count = $condition->getCount();
        $start = $condition->getOffset() == null ? 0 : $condition->getOffset();
        for($i = $start; $i < $limit && $i < $count; $i++){
            $retValue->add($result->get($i));
        }

        if($returnArray){
            return $retValue->toArray();
        }
        return $retValue;
    }

}

?>

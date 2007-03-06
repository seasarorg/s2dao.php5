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
// $Id$
//
/**
 * @author nowel
 */
class S2Dao_RelationRowCache {

    private $rowMapList = null;
    
    public function __construct($size) {
        $this->rowMapList = new S2Dao_ArrayList();
        for ($i = 0; $i < $size; ++$i) {
            $this->rowMapList->add(new S2Dao_HashMap());
        }
    }
    
    public function getRelationRow($relno, S2Dao_RelationKey $key) {
        return $this->getRowMap($relno)->get($key->hashCode());
    }
    
    public function addRelationRow($relno, S2Dao_RelationKey $key, $row) {
        $this->getRowMap($relno)->put($key->hashCode(), $row);
    }

    protected function getRowMap($relno) {
        return $this->rowMapList->get($relno);
    }
}
?>

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
class S2Dao_ListProxy extends S2Dao_ArrayList {
    
    private $args;
    protected $lst = null;
    protected $sqlCommand;

    public function __construct(S2Dao_SqlCommand $sqlCommand, array $args) {
        $this->sqlCommand = $sqlCommand;
        $this->args = $args;
    }

    private function initList() {
        if ($this->lst == null) {
            $this->lst = $this->sqlCommand->execute($this->args);
        }
    }

    public function get($index) {
        $this->initList();
        return $this->lst->get($index);
    }

    public function size() {
        $this->initList();
        return $this->lst->size();
    }

}

?>

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
 */
class S2DaoConfigurationImpl implements S2DaoConfiguration {

    /**
     * INSERT文を自動生成するメソッド名につけるprefix
     */
    protected $insertPrefixes = array('insert', 'create', 'add');
    
    public function setInsertPrefixes(array $prefixes) {
        $this->insertPrefixes = $prefixes;
    }
    
    public function isInsertMethod(ReflectionMethod $method) {
        $c = count($this->insertPrefixes);
        for ($i = 0; $i < $c; ++$i) {
            $insertPrefix = $this->insertPrefixes[$i];
            if (stripos($method->getName(), $insertPrefix) === 0) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * DELETE文を自動生成するメソッド名につけるprefix
     */
    protected $deletePrefixes = array('delete', 'remove');
    
    public function setDeletePrefixes(array $prefixes) {
        $this->deletePrefixes = $prefixes;
    }

    public function isDeleteMethod(ReflectionMethod $method) {
        $c = count($this->deletePrefixes);
        for ($i = 0; $i < $c; ++$i) {
            $deletePrefix = $this->deletePrefixes[$i];
            if (stripos($method->getName(), $deletePrefix) === 0) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * UPDATE文を自動生成するメソッド名につけるprefix
     */
    protected $updatePrefixes = array('update', 'modify', 'store');

    public function setUpdatePrefixes(array $prefixes) {
        $this->updatePrefixes = $prefixes;
    }

    public function isUpdateMethod(ReflectionMethod $method) {
        $c = count($this->updatePrefixes);
        for ($i = 0; $i < $c; ++$i) {
            $updatePrefix = $this->updatePrefixes[$i];
            if (stripos($method->getName(), $updatePrefix) === 0) {
                return true;
            }
        }
        return false;
    }
    
    public function isSelectMethod(ReflectionMethod $method) {
        if ($this->isDeleteMethod($method) ||
            $this->isInsertMethod($method) ||
            $this->isUpdateMethod($method)) {
            return false;
        }
        return true;
    }
    
    private $daoSuffixes;
    
    public function daoSuffixes(array $daoSuffixes) {
        $this->daoSuffixes = $daoSuffixes;
    }

    public function getDaoSuffixes() {
        return $this->daoSuffixes;
    }
}

?>
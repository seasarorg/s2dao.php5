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
class S2Dao_BeanArrayMetaDataResultSetHandler extends S2Dao_BeanListMetaDataResultSetHandler {

    public function __construct(S2Dao_BeanMetaData $beanMetaData) {
        parent::__construct($beanMetaData);
    }

    public function handle($rs){
        $result = parent::handle($rs);
        $arrays = array();
        $c = $result->size();
        for($i = 0; $i < $c; $i++){
            $bean = $result->get($i);
            $arrays[] = $this->dump_class($bean);
        }
        return $arrays;
    }
    
    private function dump_class($bean){
        $refClass = new ReflectionClass($bean);
        $className = $refClass->getName();
        
        $assoc = array();
        $assoc[$className] = array();
        $retVal =& $assoc[$className];
        
        $bd = S2Container_BeanDescFactory::getBeanDesc($refClass);
        $c = $bd->getPropertyDescSize();
        for($i = 0; $i < $c; $i++){
            $pd = $bd->getPropertyDesc($i);
            $propName = $pd->getPropertyName();
            $propValue = $pd->getValue($bean);
            if(is_object($propValue)){
                $retVal[$propName] = current($this->dump_class($propValue));
                continue;
            }
            $retVal[$propName] = $propValue;
        }
        return $assoc;
    }
}
?>

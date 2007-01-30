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
class S2Dao_SimpleXmlResultSetHandler extends S2Dao_BeanListMetaDataResultSetHandler {
    
    const XML_VERSION = '1.0';
    const XML_ENCODING = 'UTF-8';
    const ROOT_ELEMENT = 'root';

    public function __construct(S2Dao_BeanMetaData $beanMetaData,
                                S2Dao_Dbms $dbms,
                                array $relationPropertyHandlers) {
        parent::__construct($beanMetaData, $dbms, $relationPropertyHandlers);
        if (!extension_loaded('xmlwriter')){
            throw new Exception('resultset simplexml reqiored xmlwriter extension');
        }
    }

    public function handle(PDOStatement $rs){
        $xml = new XmlWriter();
        $xml->openMemory();
        $xml->startDocument(self::XML_VERSION, self::XML_ENCODING);
        $xml->startElement(self::ROOT_ELEMENT);
        
        $result = parent::handle($rs);
        $c = $result->size();
        for($i = 0; $i < $c; $i++){
            $this->createElement($result->get($i));
        }
        
        $xml->endElement();
        return $xml->outputMemory(true);
    }
    
    private function createElement(XmlWriter $xml, $bean){
        $refClass = new ReflectionClass($bean);
        $className = $refClass->getName();
        $tag = strtolower($className[0]) . substr($className, 1);
        
        $bd = S2Container_BeanDescFactory::getBeanDesc($refClass);
        $c = $bd->getPropertyDescSize();
        for($i = 0; $i < $c; $i++){
            $pd = $bd->getPropertyDesc($i);
            $propName = $pd->getPropertyName();
            $propValue = $pd->getValue($bean);
            if(is_object($propValue)){
                $xml->startElement($tag);
                $this->createElement($xml, $propValue);
                $xml->endElement();
                continue;
            }
            $xml->writeElement($tag, $propValue);
        }
    }
}
?>

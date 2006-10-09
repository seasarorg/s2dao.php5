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
class S2Dao_ResultSetHandlerFactoryImpl implements S2Dao_ResultSetHandlerFactory {
    
    private $beanMetaData;
    private $annotationReader;
    
    public function __construct(S2Dao_BeanMetaData $bmd,
                                S2Dao_FieldDaoAnnotationReader $reader){
        $this->beanMetaData = $bmd;
        $this->annotationReader = $reader;
    }
    
    public function createResultSetHandler(ReflectionMethod $method) {
        $type = $this->annotationReader->getReturnType($method);
        switch($type){
            case S2Dao_DaoAnnotationReader::RETURN_LIST:
                return new S2Dao_BeanListMetaDataResultSetHandler($this->beanMetaData);
            case S2Dao_DaoAnnotationReader::RETURN_ARRAY:
                return new S2Dao_BeanArrayMetaDataResultSetHandler($this->beanMetaData);
            case S2Dao_DaoAnnotationReader::RETURN_YAML:
                return new S2Dao_BeanYamlMetaDataResultSetHandler($this->beanMetaData);
            case S2Dao_DaoAnnotationReader::RETURN_JSON:
                return new S2Dao_BeanJsonMetaDataResultSetHandler($this->beanMetaData);
            case S2Dao_DaoAnnotationReader::RETURN_OBJ:
            default:
                if($type === null){
                    return new S2Dao_BeanMetaDataResultSetHandler($this->beanMetaData);
                }
                return new S2Dao_ObjectResultSetHandler();
        }
    }
}

?>
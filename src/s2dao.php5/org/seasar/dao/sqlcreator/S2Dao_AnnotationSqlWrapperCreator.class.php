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
class S2Dao_AnnotationSqlWrapperCreator implements S2Dao_SqlWrapperCreator {

    private $annotationReaderFactory;

    /**
     * 
     */
    public function __construct(S2Dao_AnnotationReaderFactory $annotationReaderFactory) {
        $this->annotationReaderFactory = $annotationReaderFactory;
    }

    /**
     * @return SqlWrapper
     */
    public function createSqlCommand(S2Dao_Dbms $dbms, S2Dao_DaoMetaData $daoMetaData,
                                     S2Dao_BeanMetaData $beanMetaData,
                                     ReflectionMethod $method) {
        $beanDesc = $daoMetaData->getDaoBeanDesc();
        $reader = $this->annotationReaderFactory->createDaoAnnotationReader($beanDesc);
        $sql = $reader->getSQL($method, $dbms->getSuffix());
        if ($sql !== null) {
            return new S2Dao_SqlWrapperImpl($reader->getArgNames($method), $sql);
        }
        return null;
    }

}

?>
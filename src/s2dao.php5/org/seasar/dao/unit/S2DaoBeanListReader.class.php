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
// $Id: $
//
/**
 * @author nowel
 * @package org.seasar.s2dao.unit
 */
class S2DaoBeanListReader extends S2DaoBeanReader {

    public function __construct(S2Dao_ArrayList $list, PDO $dbMetaData) {
        $this->dataSet = new S2Dao_DataSetImpl();
        $this->table = $this->dataSet->addTable('S2DaoBean');
        
        $dbms = S2DaoDbmsManager::getDbms($dbMetaData);
        $clazz = new ReflectionClass(get_class($list->get(0)));
        $beanMetaData = new S2Dao_BeanMetaDataImpl();
        $beanMetaData->setBeanClass($clazz);
        $beanMetaData->setDatabaseMetaData($dbMetaData);
        $beanMetaData->setAnnotationReaderFactory(new S2Dao_FieldAnnotationReaderFactory());
        $beanMetaData->setValueTypeFactory(new S2Dao_ValueTypeFactoryImpl());
        $beanMetaData->initialize();
        $this->setupColumns($beanMetaData);
        for ($i = 0; $i < $list->size(); ++$i) {
            $this->setupRow($beanMetaData, $list->get($i));
        }
    }

}
?>

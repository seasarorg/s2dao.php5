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
 * @package org.seasar.s2dao.sqlcreator
 */
class S2Dao_FileSqlWrapperCreator implements S2Dao_SqlWrapperCreator {

    protected $encoding = 'auto';
    private $annotationReaderFactory;

    public function __construct(S2Dao_AnnotationReaderFactory $annotationReaderFactory) {
        $this->annotationReaderFactory = $annotationReaderFactory;
    }

    protected function readText($path) {
        //return mb_convert_encoding(file_get_contents($path), $this->encoding);
        return file_get_contents($path);
    }

    /**
     * @return SqlWrapper
     */
    public function createSqlCommand(S2Dao_Dbms $dbms,
                                     S2Dao_DaoMetaData $daoMetaData,
                                     S2Dao_BeanMetaData $beanMetaData,
                                     ReflectionMethod $method) {
        $daoBeanDesc = $daoMetaData->getDaoBeanDesc();
        $reader = $this->annotationReaderFactory->createDaoAnnotationReader($daoBeanDesc);
        $daoClass = $daoBeanDesc->getBeanClass();
        $command = $this->createSqlCommand2($dbms, $reader, $daoClass, $beanMetaData, $method);
        if ($command !== null) {
            return $command;
        }
        $interfaces = $daoClass->getInterfaces();
        $c = count($interfaces);
        for ($i = 0; $i < $c; $i++) {
            $interface = $interfaces[$i];
            $interfaceMethod = $this->getSameSignatureMethod($interface, $method);
            if ($interfaceMethod !== null) {
                $command = $this->createSqlCommand2($dbms, $reader, $interface,
                                    $beanMetaData, $interfaceMethod);
                if ($command !== null) {
                    return $command;
                }
            }
        }
        return null;
    }

    /**
     * @return SqlWrapper
     */
    protected function createSqlCommand2(S2Dao_Dbms $dbms,
                                         S2Dao_DaoAnnotationReader $reader,
                                         ReflectionClass $clazz,
                                         S2Dao_BeanMetaData $beanMetaData,
                                         ReflectionMethod $method) {
        $dir = dirname($clazz->getFileName()) . DIRECTORY_SEPARATOR;
        $base = $dir . $clazz->getName() . '_' . $method->getName();
        $dbmsPath = $base . $dbms->getSuffix() . '.sql';
        $standardPath = $base . '.sql';
        $argNames = $reader->getArgNames($method);
        if (file_exists($dbmsPath)) {
            return new S2Dao_SqlWrapperImpl($argNames, $this->readText($dbmsPath));
        } else if (file_exists($standardPath)) {
            return new S2Dao_SqlWrapperImpl($argNames, $this->readText($standardPath));
        }
        return null;
    }

    private function getSameSignatureMethod(ReflectionClass $clazz, ReflectionMethod $method) {
        try {
            return S2Container_ClassUtil::getMethod($clazz, $method->getName());
        } catch (S2Container_NoSuchMethodRuntimeException $e) {
            return null;
        }
    }

}

?>
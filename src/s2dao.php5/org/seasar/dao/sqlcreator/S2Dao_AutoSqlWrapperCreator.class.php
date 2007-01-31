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
abstract class S2Dao_AutoSqlWrapperCreator implements S2Dao_SqlWrapperCreator {
    
    protected $annotationReaderFactory;
    protected $configuration;

    public function __construct(
            S2Dao_AnnotationReaderFactory $annotationReaderFactory,
            S2Dao_DaoNamingConvention $configuration) {
        $this->annotationReaderFactory = $annotationReaderFactory;
        $this->configuration = $configuration;
    }

    protected function createUpdateWhere(S2Dao_BeanMetaData $beanMetaData) {
        $buf = '';
        $buf .= ' WHERE ';
        $c = $beanMetaData->getPrimaryKeySize();
        for ($i = 0; $i < $c; ++$i) {
            if (0 < $i) {
                $buf .= ' AND ';
            }
            $pkName = $beanMetaData->getPrimaryKey($i);
            $buf .= $pkName;
            $buf .= ' = /*dto.';
            $buf .= $beanMetaData->getPropertyType($pkName)->getPropertyName();
            $buf .= '*/';
        }
        if ($beanMetaData->hasVersionNoPropertyType()) {
            $pt = $beanMetaData->getVersionNoPropertyType();
            $buf .= ' AND ';
            $buf .= $pt->getColumnName();
            $buf .= ' = /*dto.';
            $buf .= $pt->getPropertyName();
            $buf .= '*/';
        }
        if ($beanMetaData->hasTimestampPropertyType()) {
            $pt = $beanMetaData->getTimestampPropertyType();
            $buf .= ' AND ';
            $buf .= $pt->getColumnName();
            $buf .= ' = /*dto.';
            $buf .= $pt->getPropertyName();
            $buf .= '*/';
        }
        return $buf;
    }

    protected function isUpdateSignatureForBean(S2Dao_BeanMetaData $beanMetaData,
                                                ReflectionMethod $method) {
        $parameters = $method->getParameters();
        if(count($parameters) == 1){
            $param0 = $parameters[0];
            return $beanMetaData->isBeanClassAssignable($param0->getClass());
        }
        return false;
    }

    protected function checkAutoUpdateMethod(S2Dao_BeanMetaData $beanMetaData,
                                             ReflectionMethod $method) {
        $parameters = $method->getParameters();
        $param0 = $parameters[0];
        $paramClass = $param0->getClass();
        return count($parameters) === 1
                && (
                    $beanMetaData->isBeanClassAssignable($paramClass)
                    || $paramClass->isSubClassOf('S2Dao_List')
                    || $param0->isArray()
                );
    }

    protected function isPropertyExist(array $props, $propertyName) {
        $c = count($props);
        for ($i = 0; $i < $c; ++$i) {
            if (strcasecmp($props[$i], $propertyName) == 0) {
                return true;
            }
        }
        return false;
    }

    protected function getPersistentPropertyNames(
                S2Dao_DaoAnnotationReader $daoAnnotationReader,
                S2Dao_BeanMetaData $beanMetaData,
                ReflectionMethod $method) {
        $names = new S2Dao_ArrayList();
        $props = $daoAnnotationReader->getNoPersistentProps($method);
        if ($props != null) {
            $c = $beanMetaData->getPropertyTypeSize();
            for ($i = 0; $i < $c; ++$i) {
                $pt = $beanMetaData->getPropertyType($i);
                if ($pt->isPersistent()
                        && !$this->isPropertyExist($props, $pt->getPropertyName())) {
                    $names->add($pt->getPropertyName());
                }
            }
        } else {
            $props = $daoAnnotationReader->getPersistentProps($method);
            if ($props != null) {
                $names->addAll($props);
                $c = $beanMetaData->getPrimaryKeySize();
                for ($i = 0; $i < $c; ++$i) {
                    $pk = $beanMetaData->getPrimaryKey($i);
                    $pt = $beanMetaData->getPropertyTypeByColumnName($pk);
                    $names->add($pt->getPropertyName());
                }
                if ($beanMetaData->hasVersionNoPropertyType()) {
                    $names->add($beanMetaData->getVersionNoPropertyName());
                }
                if ($beanMetaData->hasTimestampPropertyType()) {
                    $names->add($beanMetaData->getTimestampPropertyName());
                }
            }
        }
        if ($names->size() == 0) {
            $size = $beanMetaData->getPropertyTypeSize();
            for ($i = 0; $i < $size; ++$i) {
                $pt = $beanMetaData->getPropertyType($i);
                if ($pt->isPersistent()) {
                    $names->add($pt->getPropertyName());
                }
            }
        }
        return $names->toArray();
    }

}

?>
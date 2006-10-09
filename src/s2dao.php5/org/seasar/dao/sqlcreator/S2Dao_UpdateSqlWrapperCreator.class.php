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
class S2Dao_UpdateSqlWrapperCreator extends S2Dao_AutoSqlWrapperCreator {
    
    private $versionNoPropertyExists;

    private $timeStampPropertyExists;

    public function __construct(
            S2Dao_AnnotationReaderFactory $annotationReaderFactory,
            S2DaoConfiguration $configuration) {
        super($annotationReaderFactory, $configuration);
    }

    protected function createSql(S2Dao_BeanMetaData $beanMetaData,
                                 array $propertyNames,
                                 $updateProperty) {
        $beanMetaData->checkPrimaryKey();
        $buf = '';
        $buf .= 'UPDATE ';
        $buf .= $beanMetaData->getTableName();
        $buf .= ' SET ';
        $propertyTypes = $this->getPropertyTypes($beanMetaData, $propertyNames);
        $timestampPropertyName = $beanMetaData->getTimestampPropertyName();
        $versionNoPropertyName = $beanMetaData->getVersionNoPropertyName();
        $c = count($propertyTypes);
        for ($i = 0; $i < $c; ++$i) {
            if (0 < $i) {
                $buf .= ', ';
            }
            $pt = $propertyTypes[$i];
            $buf .= $pt->getColumnName();
            if ($updateProperty &&
                strcasecmp($pt->getPropertyName(), $timestampPropertyName) == 0) {
                $buf .= ' = /*_timeStamp*/';
                $this->timeStampPropertyExists = true;
            } else if ($updateProperty &&
                    strcmp($pt->getPropertyName(), $versionNoPropertyName) == 0) {
                $buf .= ' = /*_versionNo*/';
                $this->versionNoPropertyExists = true;
            } else {
                $buf .= ' = /*dto.';
                $buf .= $pt->getPropertyName();
                $buf .= '*/';
            }
        }
        $buf .= $this->createUpdateWhere($beanMetaData);
        return $buf;
    }

    /**
     * @return PropertyType[]
     */
    protected function getPropertyTypes(S2Dao_BeanMetaData $beanMetaData,
                                        array $propertyNames) {
        $types = new S2Dao_ArrayList();
        $c = count($propertyNames);
        for ($i = 0; $i < $c; ++$i) {
            $pt = $beanMetaData->getPropertyType($propertyNames[$i]);
            if ($pt->isPrimaryKey()) {
                continue;
            }
            $types->add($pt);
        }
        return $types->toArray();
    }

    /**
     * @return SqlWrapper
     */
    public function createSqlCommand(S2Dao_Dbms $dbms,
                                     S2Dao_DaoMetaData $daoMetaData,
                                     S2Dao_BeanMetaData $beanMetaData,
                                     ReflectionMethod $method) {
        if (!$this->configuration->isUpdateMethod($method)) {
            return null;
        }
        $beanDesc = $daoMetaData->getDaoBeanDesc();
        $daoAnnotationReader = $this->annotationReaderFactory->createDaoAnnotationReader($beanDesc);
        $this->checkAutoUpdateMethod($beanMetaData, $method);
        $propertyNames = $this->getPersistentPropertyNames($daoAnnotationReader, $beanMetaData, $method);
        if ($this->isUpdateSignatureForBean($beanMetaData, $method)) {
            $anSqlWrapper = new S2Dao_SqlWrapperImplAnony(array('dto'),
                    $this->createSql($beanMetaData, $propertyNames, true), false);
                    
            $anSqlWrapper->versionNoPropertyExists = $this->versionNoPropertyExists;
            $anSqlWrapper->timeStampPropertyExists = $this->timeStampPropertyExists;
            $anSqlWrapper->beanMetaData = $beanMetaData;
            
            return $anSqlWrapper;
        }
        return new S2Dao_SqlWrapperImpl(array('dto'),
                    $this->createSql($beanMetaData, $propertyNames, false), true);
    }

}

final class S2Dao_SqlWrapperImplAnony extends S2Dao_SqlWrapperImpl {
    
    public $timeStampPropertyExists;
    public $versionNoPropertyExists;
    public $beanMetaData;
    
    public function preUpdateBean(S2Dao_CommandContext $ctx) {
        if ($this->timeStampPropertyExists) {
            $ctx->addArg('_timeStamp', date());
        }
        if ($this->versionNoPropertyExists) {
            $bean = $ctx->getArg('dto');
            $versionNoPropertyName = $this->beanMetaData->getVersionNoPropertyName();
            $pt = $this->beanMetaData->getPropertyType($versionNoPropertyName);
            $vn = $pt->getPropertyDesc()->getValue($bean);
            $ctx->addArg('_versionNo', ((int)$vn + 1), gettype(0));
        }
    }

    public function postUpdateBean(S2Dao_CommandContext $ctx, $returnValue) {
        $rows = $returnValue;
        if ((int)$rows != 1) {
            throw new S2Dao_NotSingleRowUpdatedRuntimeException(
                        $ctx->getArg('dto'), (int)$rows);
        }
        if ($this->timeStampPropertyExists) {
            $bean = $ctx->getArg('dto');
            $timestampPropertyName = $beanMetaData->getTimestampPropertyName();
            $pt = $this->beanMetaData->getPropertyType($timestampPropertyName);
            $vn = $ctx->getArg('_timeStamp');
            $pt->getPropertyDesc()->setValue($bean, $vn);
        }
        if ($this->versionNoPropertyExists) {
            $bean = $ctx->getArg('dto');
            $versionNoPropertyName = $this->beanMetaData->getVersionNoPropertyName();
            $pt = $this->beanMetaData->getPropertyType($versionNoPropertyName);
            $vn = $ctx->getArg('_versionNo');
            $pt->getPropertyDesc()->setValue($bean, $vn);
        }
    }
}

?>
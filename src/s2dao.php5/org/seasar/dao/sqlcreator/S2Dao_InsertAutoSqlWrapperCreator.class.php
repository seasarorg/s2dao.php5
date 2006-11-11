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
class S2Dao_InsertAutoSqlWrapperCreator extends S2Dao_AutoSqlWrapperCreator {
    
    private $dataSource;

    public function __construct(
            S2Dao_AnnotationReaderFactory $annotationReaderFactory,
            S2Container_DataSource $dataSource,
            S2Dao_StatementFactory $statementFactory,
            S2Dao_DaoNamingConvention $configuration) {
        parent::__construct($annotationReaderFactory, $configuration);
        $this->dataSource = $dataSource;
    }

    protected function getPropertyTypes(S2Dao_BeanMetaData $beanMetaData,
                                        array $propertyNames) {
        $types = new S2Dao_ArrayList();
        $c = count($propertyNames);
        for ($i = 0; $i < $c; ++$i) {
            $pt = $beanMetaData->getPropertyType($propertyNames[$i]);
            $types->add($pt);
        }
        return $types->toArray();
    }

    /**
     * @return SqlWrapper
     */
    protected function createInsertSql(S2Dao_BeanMetaData $beanMetaData,
                                       S2Dao_Dbms $dbms,
                                       array $propertyNames) {
        $versionNoPropertyExists = false;
        $timeStampPropertyExists = false;
        $refBeanMeta = new ReflectionClass($beanMetaData);
        $reader = $this->annotationReaderFactory->createBeanAnnotationReader($refBeanMeta);
        $propertyTypes = $this->getPropertyTypes($beanMetaData, $propertyNames);
        $namesBuf = '';
        $valuesBuf = '';
        $timestampPropertyName = $beanMetaData->getTimestampPropertyName();
        $versionNoPropertyName = $beanMetaData->getVersionNoPropertyName();
        $generator = null;
        $c = count($propertyTypes);
        for ($i = 0; $i < $c; ++$i) {
            $isNullCheckRequired = true;
            $pt = $propertyTypes[$i];
            $id = $reader->getId($pt->getPropertyDesc());
            if($id != null){
                $generator = IdentifierGeneratorFactory::createIdentifierGenerator(
                                    $pt->getPropertyName(), $dbms, $id);
                if(!$generator->isSelfGenerate()){
                    continue;
                }
            }
            $pd = $pt->getPropertyDesc();
            $propertyName = $pd->getPropertyName();
            if ($pd->getPropertyType()->isPrimitive()
                    || strcasecmp($propertyName, $timestampPropertyName) == 0
                    || strcmp($propertyName, $versionNoPropertyName) == 0) {
                $isNullCheckRequired = false;
            }
            if ($isNullCheckRequired) {
                $namesBuf .= '/*IF dto.';
                $namesBuf .= $propertyName;
                $namesBuf .= ' != null*/';

                $valuesBuf .= '/*IF dto.';
                $valuesBuf .= $propertyName;
                $valuesBuf .= ' != null*/';
            }
            $namesBuf .= ',';
            $valuesBuf .= ',';
            $namesBuf .= $pt->getColumnName();
            if (strcasecmp($pt->getPropertyName(), $timestampPropertyName) == 0) {
                $valuesBuf .= '/*_timeStamp*/';
                $timeStampPropertyExists = true;
            } else if (strcmp($pt->getPropertyName(), $versionNoPropertyName) == 0) {
                $valuesBuf .= '/*_versionNo*/';
                $versionNoPropertyExists = true;
            } else {
                $valuesBuf .= '/*dto.';
                $valuesBuf .= $propertyName;
                $valuesBuf .= '*/';
            }
            if ($isNullCheckRequired) {
                $namesBuf .= '/*END*/';
                $valuesBuf .= '/*END*/';
            }
        }
        if($generator == null){
            $generator = IdentifierGeneratorFactory::createIdentifierGenerator(null, $dbms);
        }
        $sql = 'INSERT INTO ' . $beanMetaData->getTableName() . ' (' . $namesBuf;
        $sql .= ') VALUES (' . $valuesBuf . ')';
        return new S2Dao_InsertSqlWrapper(array('dto'),
                                          $sql,
                                          $beanMetaData,
                                          $this->dataSource,
                                          $generator,
                                          $timeStampPropertyExists,
                                          $versionNoPropertyExists);
    }
    
    protected function createBatchInsertSql(S2Dao_BeanMetaData $beanMetaData,
                                            S2Dao_Dbms $dbms,
                                            array $propertyNames) {
        $refBeanMeta = new ReflectionClass($beanMetaData);
        $reader = $this->annotationReaderFactory->createBeanAnnotationReader($refBeanMeta);
        $versionNoPropertyExists = false;
        $timeStampPropertyExists = false;
        $timestampPropertyName = $beanMetaData->getTimestampPropertyName();
        $versionNoPropertyName = $beanMetaData->getVersionNoPropertyName();
        $propertyTypes = $this->getPropertyTypes($beanMetaData, $propertyNames);
        $identifierGenerator = null;
        $namesBuf = '';
        $valuesBuf = '';
        $commaRequired = false;
        $c = count($propertyTypes);
        for ($i = 0; $i < $c; ++$i) {
            $pt = $propertyTypes[$i];
            $id = $reader->getId($pt->getPropertyDesc());
            if($id != null){
                $identifierGenerator = IdentifierGeneratorFactory::createIdentifierGenerator(
                                            $pt->getPropertyName(), $dbms, $id);
                if(!$identifierGenerator->isSelfGenerate()){
                    continue;
                }
            }
            if($commaRequired){
                $namesBuf .= ', ';
                $valuesBuf .= ', ';
            }
            $namesBuf .= $pt->getColumnName();
            if (strcasecmp($pt->getPropertyName(), $timestampPropertyName) == 0) {
                $valuesBuf .= '/*_timeStamp*/';
                $timeStampPropertyExists = true;
            } else if (strcmp($pt->getPropertyName(), $versionNoPropertyName) == 0) {
                $valuesBuf .= '/*_versionNo*/';
                $versionNoPropertyExists = true;
            } else {
                $valuesBuf .= '/*dto.';
                $valuesBuf .= $pt->getPropertyName();
                $valuesBuf .= '*/';
            }
            $commaRequired = true;
        }
        $sql = 'INSERT INTO ' . $beanMetaData->getTableName() . ' (' . $namesBuf;
        $sql .= ') VALUES (' . $valuesBuf . ')';
        return new S2Dao_InsertBatchSqlWrapper(array('dto'),
                                               $sql,
                                               $beanMetaData,
                                               $timeStampPropertyExists,
                                               $versionNoPropertyExists);
    }

    /**
     * @return SqlWrapper
     */
    public function createSqlCommand(S2Dao_Dbms $dbms,
                                     S2Dao_DaoMetaData $daoMetaData,
                                     S2Dao_BeanMetaData $beanMetaData,
                                     ReflectionMethod $method) {
        if (!$this->configuration->isInsertMethod($method)) {
            return null;
        }
        if (!$this->checkAutoUpdateMethod($beanMetaData, $method)) {
            throw new S2Dao_IllegalSignatureRuntimeException('EDAO0006', $method->__toString());            
        }
        $beanDesc = $daoMetaData->getDaoBeanDesc();
        $daoAnnotationReader = $this->annotationReaderFactory->createDaoAnnotationReader($beanDesc);
        $propertyNames = $this->getPersistentPropertyNames($daoAnnotationReader, $beanMetaData, $method);
        if ($this->isUpdateSignatureForBean($beanMetaData, $method)) {
            return $this->createInsertSql($beanMetaData, $dbms, $propertyNames);
        }
        return $this->createBatchInsertSql($beanMetaData, $dbms, $propertyNames);
    }

}

final class S2Dao_InsertSqlWrapper extends S2Dao_SqlWrapperImpl {
    
    private $beanMetaData;
    private $dataSource;
    private $timeStampPropertyExists;
    private $versionNoPropertyExists;
    private $generator;
    
    public function __construct(array $parameterNames,
                                $sql,
                                S2Dao_BeanMetaData $beanMetaData,
                                S2Container_DataSource $dataSource,
                                S2Dao_IdentifierGenerator $generator,
                                $timeStampPropertyExists,
                                $versionNoPropertyExists) {
        parent::__construct($parameterNames, $sql, false);
        $this->beanMetaData = $beanMetaData;
        $this->dataSource = $dataSource;
        $this->timeStampPropertyExists = $timeStampPropertyExists;
        $this->versionNoPropertyExists = $versionNoPropertyExists;
        $this->generator = $generator;
    }

    public function transformSql($sql) {
        // delete comma
        $newSql = preg_replace('/\([^,]*,/', '(', $sql);
        if (preg_match('/\(\s*\)/', $newSql)) {
            throw new S2Container_S2RuntimeException('EDAO0014');
        }
        return $newSql;
    }

    public function preUpdateBean(S2Dao_CommandContext $ctx) {
        $bean = $ctx->getArg('dto');
        if ($bean === null) {
            return;
        }
        if ($this->generator->isSelfGenerate()) {
            $this->generator->setIdentifier($bean, $this->dataSource);
        }
        if ($this->timeStampPropertyExists) {
            $ctx->addArg('_timeStamp', date(), gettype(0));
        }
        if ($this->versionNoPropertyExists) {
            $ctx->addArg('_versionNo', 0, gettype(0));
        }
    }

    public function postUpdateBean(S2Dao_CommandContext $ctx, $returnValue) {
        $bean = $ctx->getArg('dto');
        if ($bean === null) {
            return;
        }
        if (!$this->generator->isSelfGenerate()) {
            $this->generator->setIdentifier($bean, $this->dataSource);
        }
        $rows = $returnValue;
        if ((int)$rows != 1) {
            throw new S2Dao_NotSingleRowUpdatedRuntimeException($bean, (int)$rows);
        }
        if ($this->timeStampPropertyExists) {
            $timestampPropertyName = $this->beanMetaData->getTimestampPropertyName();
            $pt = $this->beanMetaData->getPropertyType($this->timestampPropertyName);
            $vn = $ctx->getArg('_timeStamp');
            $pt->getPropertyDesc()->setValue($bean, $vn);
        }
        if ($this->versionNoPropertyExists) {
            $versionNoPropertyName = $this->beanMetaData->getVersionNoPropertyName();
            $pt = $this->beanMetaData->getPropertyType($this->versionNoPropertyName);
            $vn = $ctx->getArg('_versionNo');
            $pt->getPropertyDesc()->setValue($bean, $vn);
        }
    }
}

final class S2Dao_InsertBatchSqlWrapper extends S2Dao_SqlWrapperImpl{
    
    private $beanMetaData;
    private $timeStampPropertyExists;
    private $versionNoPropertyExists;
    
    public function __construct(array $parameterNames,
                                $sql,
                                S2Dao_BeanMetaData $beanMetaData,
                                $timeStampPropertyExists,
                                $versionNoPropertyExists) {
        parent::__construct($parameterNames, $sql, true);
        $this->beanMetaData = $beanMetaData;
        $this->timeStampPropertyExists = $timeStampPropertyExists;
        $this->versionNoPropertyExists = $versionNoPropertyExists;
    }

    public function preUpdateBean(S2Dao_CommandContext $ctx) {
        $bean = $ctx->getArg('dto');
        if ($bean === null) {
            return;
        }
        if ($this->timeStampPropertyExists) {
            $ctx->addArg('_timeStamp', date(), gettype(0));
        }
        if ($this->versionNoPropertyExists) {
            $ctx->addArg('_versionNo', 0, gettype(0));
        }
    }

    public function postUpdateBean(S2Dao_CommandContext $ctx, $returnValue) {
        $bean = $ctx->getArg('dto');
        if ($bean === null) {
            return;
        }
        $rows = $returnValue;
        if ((int)$rows != 1) {
            throw new S2Dao_NotSingleRowUpdatedRuntimeException($bean, (int)$rows);
        }
        if ($this->timeStampPropertyExists) {
            $timestampPropertyName = $this->beanMetaData->getTimestampPropertyName();
            $pt = $this->beanMetaData->getPropertyType($timestampPropertyName);
            $vn = $ctx->getArg('_timeStamp');
            $pt->getPropertyDesc()->setValue($bean, $vn);
        }
        if ($this->versionNoPropertyExists) {
            $versionNoPropertyName = $this->beanMetaData->getVersionNoPropertyName();
            $pt = $this->beanMetaData->getPropertyType($versionNoPropertyName);
            $vn = $ctx->getArg('_versionNo');
            $pt->getPropertyDesc()->setValue($bean, $vn);
        }
    }
}

?>
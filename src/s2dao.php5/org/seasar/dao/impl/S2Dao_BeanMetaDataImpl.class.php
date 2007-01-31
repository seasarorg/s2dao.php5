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
 * @package org.seasar.s2dao.impl
 */
class S2Dao_BeanMetaDataImpl extends S2Dao_DtoMetaDataImpl implements S2Dao_BeanMetaData {

    private static $logger;
    private static $code = 0;
    private $tableName;
    private $propertyTypesByColumnName;
    private $relationPropertyTypes;
    private $oneToMenyRelationPropertyTypes;
    private $primaryKeys = array();
    private $autoSelectList;
    private $relation = false;
    private $identifierGenerator;
    private $versionNoPropertyName = 'versionNo';
    private $timestampPropertyName = 'timestamp';
    private $annotationReaderFactory;
    private $databaseMetaData; 
    
    public function __construct() {
        parent::__construct();
        $this->propertyTypesByColumnName = new S2Dao_CaseInsensitiveMap();
        $this->relationPropertyTypes = new S2Dao_ArrayList();
        $this->oneToMenyRelationPropertyTypes = new S2Dao_ArrayList();
        $this->annotationReaderFactory = new S2Dao_FieldAnnotationReaderFactory();
        self::$logger = S2Container_S2Logger::getLogger(get_class($this));
        self::$code++;
    }
    
    protected function getAnnotationReaderFactory() {
        return $this->annotationReaderFactory;
    }
    
    public function setAnnotationReaderFactory(
                    S2Dao_AnnotationReaderFactory $annotationReaderFactory) {
        $this->annotationReaderFactory = $annotationReaderFactory;
    }
    
    public function initialize() {
        $factory = $this->getAnnotationReaderFactory();
        $this->beanAnnotationReader = $factory->createBeanAnnotationReader($this->getBeanClass());
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($this->getBeanClass());
        $this->setupTableName($beanDesc);
        $this->setupVersionNoPropertyName($beanDesc);
        $this->setupTimestampPropertyName($beanDesc);
        $this->setupProperty($beanDesc, $this->databaseMetaData);
        $this->setupDatabaseMetaData($beanDesc, $this->databaseMetaData);
        $this->setupPropertiesByColumnName();
        $this->setupRelationProperties($beanDesc, $this->databaseMetaData);
    }
    
    protected function setupRelationProperties(S2Container_BeanDesc $beanDesc,
                                               $dbMetaData) {
        $c = $beanDesc->getPropertyDescSize();
        for ($i = 0; $i < $c; ++$i) {
            $pd = $beanDesc->getPropertyDesc($i);
            $relationType = $this->beanAnnotationReader->getRelationType($pd);
            if ($relationType !== S2Dao_RelationType::$hasMany) {
                continue;
            }
            $joinTableName = $beanAnnotationReader->getRelationTable($pd);
            $relationPropertyType = null;
            if ($joinTableName === null) {
                $relationPropertyType = $this->createOneToManyRelationPropertyType(
                            $beanDesc, $pd, $dbMetaData);
            } else {
                $relationPropertyType = $this->createManyToManyRelationPropertyType(
                            $beanDesc, $pd, $dbMetaData, $joinTableName);
            }
            $this->addOneToManyRelationPropertyType($relationPropertyType);
        }
    }
    
    /**
     * @return RelationPropertyType
     */
    private function createOneToManyRelationPropertyType(S2Container_BeanDesc $beanDesc,
                                                         S2Container_PropertyDesc $propertyDesc,
                                                         $dbMetaData,
                                                         $joinTableName = null) {
        if($joinTableName === null){
            return $this->createOneToManyRelationPropertyType0($beanDesc,
                                                               $propertyDesc,
                                                               $dbMetaData);
        }
        return $this->createOneToManyRelationPropertyType1($beanDesc,
                                                           $propertyDesc,
                                                           $dbMetaData,
                                                           $joinTableName);
    }
    
    private function createOneToManyRelationPropertyType0(S2Container_BeanDesc $beanDesc,
                                                          S2Container_PropertyDesc $propertyDesc,
                                                          $dbMetaData){
        $myKeys = array();
        $yourKeys = array();
        $beanClass = $this->beanAnnotationReader->getRelationBean($propertyDesc);
        
        // 
        $returnType = $propertyDesc->getPropertyType();
        if ($returnType->isArray()) {
            $beanClass = $returnType->getComponentType();
        }
        if ($beanClass === null) {
            // TODO
            throw new S2Container_SRuntimeException('');
        }
        $beanMetaData = new S2Dao_BeanMetaDataImpl();
        $beanMetaData->setBeanClass($beanClass);
        $beanMetaData->setDatabaseMetaData($dbMetaData);
        $beanMetaData->setAnnotationReaderFactory($this->getAnnotationReaderFactory());
        $beanMetaData->setValueTypeFactory($this->getValueTypeFactory());
        $beanMetaData->setRelation(true);
        $beanMetaData->initialize();
        $relkeys = $beanAnnotationReader->getRelationKey($propertyDesc);
        $myKeyList = new S2Dao_ArrayList();
        $yourKeyList = new S2Dao_ArrayList();
        if ($relkeys !== null) {
            $st = preg_split('/[\s,]+/', $relkeys);
            foreach($st as $token){
                $index = strpos($token, ':');
                if (0 < $index && $index !== false) {
                    $myKeyList->add(substr($token, 0, $index));
                    $yourKeyList->add(substr($token, $index + 1));
                } else {
                    $myKeyList->add($token);
                    $yourKeyList->add($token);
                }
            }
        } else {
            $primaryKeySize = $this->getPrimaryKeySize();
            for ($i = 0; $i < $primaryKeySize; $i++) {
                $myKey = $this->getPrimaryKey($i);
                $myKeyList->add($myKey);
                $column = $beanMetaData->getPropertyTypeByColumnName($myKey);
                $yourKey = $column->getColumnName();
                $yourKeyList->add($yourKey);
            }
        }
        $myKeys = $myKeyList->toArray();
        $yourKeys = $yourKeyList->toArray();
        return new S2Dao_RelationPropertyTypeImpl($propertyDesc,
                                                  $myKeys,
                                                  $yourKeys,
                                                  $beanMetaData,
                                                  S2Dao_RelationType::$hasMany);
    }

    /**
     * @return RelationPropertyType
     */
    private function createManyToManyRelationPropertyType1(
            S2Container_BeanDesc $beanDesc, S2Container_PropertyDesc $propertyDesc,
            $dbMetaData, $joinTableName) {
        $myKeys = array();
        $yourKeys = array();
        $myJoinKeys = array();
        $yourJoinKeys = array();
        $beanClass = $beanAnnotationReader->getRelationBean($propertyDesc);
        
        //
        $returnType = $propertyDesc->getPropertyType();
        if ($returnType->isArray()) {
            $beanClass = $returnType->getComponentType();
        }
        if ($beanClass === null) {
            // TODO
            throw new S2Container_SRuntimeException('');
        }
        $beanMetaData = new S2Dao_BeanMetaDataImpl();
        $beanMetaData->setBeanClass($beanClass);
        $beanMetaData->setDatabaseMetaData($dbMetaData);
        $beanMetaData->setAnnotationReaderFactory($this->getAnnotationReaderFactory());
        $beanMetaData->setValueTypeFactory($this->getValueTypeFactory());
        $beanMetaData->setRelation(true);
        $beanMetaData->initialize();
        $relkeys = $beanAnnotationReader->getRelationKey($propertyDesc);
        $myKeyList = new S2Dao_ArrayList();
        $yourKeyList = new S2Dao_ArrayList();
        $myJoinKeyList = new S2Dao_ArrayList();
        $yourJoinKeyList = new S2Dao_ArrayList();
        if ($relkeys !== null) {
            $st = preg_split('/[\s,]+/', $relkeys);
            foreach($st as $token){
                $index = strpos($token, ':');
                if (0 < $index && $index !== false) {
                    $myKeyList->add(substr($token, 0, $index));
                    $yourKeyList->add(substr($token, $index + 1));
                } else {
                    $myKeyList->add($token);
                    $yourKeyList->add($token);
                }
            }
        } else {
            $primaryKeySize = $this->getPrimaryKeySize();
            for ($i = 0; $i < $primaryKeySize; $i++) {
                $myKey = $this->getPrimaryKey($i);
                $myKeyList->add($myKey);
                $myJoinKeyList->add($myKey);
                $yourKey = $beanMetaData->getPrimaryKey($i);
                $yourKeyList->add($yourKey);
                $yourJoinKeyList->add($yourKey);
            }
        }
        $myKeys = $myKeyList->toArray();
        $yourKeys = $yourKeyList->toArray();
        $myJoinKeys = $myJoinKeyList->toArray();
        $yourJoinKeys = $yourJoinKeyList->toArray();
        return new S2Dao_RelationPropertyTypeImpl($propertyDesc,
                                                  $joinTableName,
                                                  $myKeys,
                                                  $yourKeys,
                                                  $myJoinKeys,
                                                  $yourJoinKeys,
                                                  $beanMetaData,
                                                  S2Dao_RelationType::$hasMany);
    }
    
    public function getTableName() {
        return $this->tableName;
    }

    /**
     * @see org.seasar.dao.BeanMetaData#getVersionNoPropertyType()
     */
    public function getVersionNoPropertyType(){
        return $this->getPropertyType($this->versionNoPropertyName);
    }

    /**
     * @see org.seasar.dao.BeanMetaData#getTimestampPropertyType()
     */
    public function getTimestampPropertyType() {
        return $this->getPropertyType($this->timestampPropertyName);
    }

    public function getVersionNoPropertyName() {
        return $this->versionNoPropertyName;
    }

    public function getTimestampPropertyName() {
        return $this->timestampPropertyName;
    }

    /**
     * @see org.seasar.dao.BeanMetaData#getPropertyTypeByColumnName(string)
     */
    public function getPropertyTypeByColumnName($columnName) {
        $propertyType = $this->propertyTypesByColumnName->get($columnName);
        if ($propertyType === null) {
            throw new S2Dao_ColumnNotFoundRuntimeException($this->tableName, $columnName);
        }
        return $propertyType;
    }

    public function getPropertyTypeByAliasName($alias) {
        if ($this->hasPropertyTypeByColumnName($alias)) {
            return $this->getPropertyTypeByColumnName($alias);
        }
        $index = strrpos($alias, '_');
        if ($index < 0 || $index === false) {
            throw new S2Dao_ColumnNotFoundRuntimeException($this->tableName, $alias);
        }
        $columnName = substr($alias, 0, $index);
        $relnoStr = substr($alias, $index + 1);
        $rpt = $this->getRelationPropertyType((int)$relnoStr);
        $rbmd = $rpt->getBeanMetaData();
        if (!$rbmd->hasPropertyTypeByColumnName($columnName)){
            throw new S2Dao_ColumnNotFoundRuntimeException($this->tableName, $alias);
        }
        return $rbmd->getPropertyTypeByColumnName($columnName);
    }

    /**
     * @see org.seasar.dao.BeanMetaData#hasPropertyTypeByColumnName(string)
     */
    public function hasPropertyTypeByColumnName($columnName) {
        return $this->propertyTypesByColumnName->get($columnName) !== null;
    }

    /**
     * @see org.seasar.dao.BeanMetaData#hasPropertyTypeByAliasName(string)
     */
    public function hasPropertyTypeByAliasName($alias) {
        if ($this->hasPropertyTypeByColumnName($alias)) {
            return true;
        }
        $index = strrpos($alias, '_');
        if ($index < 0 || $index === false) {
            return false;
        }
        $columnName = substr($alias, 0, $index);
        $relnoStr = substr($alias, $index + 1);
        $relno = (int)$relnoStr;
        if ($this->getRelationPropertyTypeSize() <= $relno) {
            return false;
        }
        $rpt = $this->getRelationPropertyType($relno);
        return $rpt->getBeanMetaData()->hasPropertyTypeByColumnName($columnName);
    }

    /**
     * @see org.seasar.dao.BeanMetaData#hasVersionNoPropertyType()
     */
    public function hasVersionNoPropertyType() {
        return $this->hasPropertyType($this->versionNoPropertyName);
    }

    /**
     * @see org.seasar.dao.BeanMetaData#hasTimestampPropertyType()
     */
    public function hasTimestampPropertyType() {
        return $this->hasPropertyType($this->timestampPropertyName);
    }

    /**
     * @see org.seasar.dao.BeanMetaData#convertFullColumnName(string)
     */
    public function convertFullColumnName($alias) {
        if ($this->hasPropertyTypeByColumnName($alias)) {
            return $this->tableName . '.' . $alias;
        }
        $index = strrpos($alias, '_');
        if ($index < 0 || $index === false) {
            throw new S2Dao_ColumnNotFoundRuntimeException($this->tableName, $alias);
        }
        $columnName = substr($alias, 0, $index);
        $relnoStr = substr($alias, $index + 1);
        $rpt = $this->getRelationPropertyType((int)$relnoStr);
        if (!$rpt->getBeanMetaData()->hasPropertyTypeByColumnName($columnName)) {
            throw new S2Dao_ColumnNotFoundRuntimeException($this->tableName, $alias);
        }
        return $rpt->getPropertyName() . '.' . $columnName;
    }
    
    /**
     * @see org.seasar.dao.BeanMetaData#getRelationPropertyTypeSize()
     */
    public function getOneToManyRelationPropertyTypeSize() {
        return $this->oneToMenyRelationPropertyTypes->size();
    }
    
    /**
     * @see org.seasar.dao.BeanMetaData#getRelationPropertyType(int)
     * @return RelationPropertyType
     */
    public function getManyRelationPropertyType($index) {
        return $this->oneToMenyRelationPropertyTypes->get($index);
    }

    /**
     * @see org.seasar.dao.BeanMetaData#getRelationPropertyTypeSize()
     */
    public function getRelationPropertyTypeSize() {
        return $this->relationPropertyTypes->size();
    }
    
    /**
     * @see org.seasar.dao.BeanMetaData#getRelationPropertyType(int)
     * @return RelationPropertyType
     */
    public function getRelationPropertyType($index) {
        if(is_integer($index)){
          return $this->relationPropertyTypes->get($index);
        } else {
            $propertyName = $index;
            $c = $this->getRelationPropertyTypeSize();
            for ($i = 0; $i < $c; $i++) {
                $rpt = $this->relationPropertyTypes->get($i);
                if ($rpt !== null &&
                    strcasecmp($rpt->getPropertyName(), $propertyName) == 0){
                    return $rpt;
                }
            }
            throw new S2Container_PropertyNotFoundRuntimeException($this->getBeanClass(),
                                                                   $propertyName);
        }
    }

    protected function setupTableName(S2Container_BeanDesc $beanDesc) {
        $ta = $this->beanAnnotationReader->getTableAnnotation();
        if ($ta != null) {
            $this->tableName = $ta;
        } else {
            $this->tableName = $this->getBeanClass()->getName();
        }
    }

    protected function setupVersionNoPropertyName(S2Container_BeanDesc $beanDesc) {
        $vna = $this->beanAnnotationReader->getVersionNoPropertyNameAnnotation();
        if ($vna !== null) {
            $this->versionNoPropertyName = $vna;
        }
    }

    protected function setupTimestampPropertyName(S2Container_BeanDesc $beanDesc) {
        $tsa = $this->beanAnnotationReader->getTimestampPropertyName();
        if ($tsa !== null) {
            $this->timestampPropertyName = $tsa;
        }
    }
    
    protected function setupProperty(S2Container_BeanDesc $beanDesc, PDO $dbMetaData) {
        $c = $beanDesc->getPropertyDescSize();
        for ($i = 0; $i < $c; ++$i) {
            $pd = $beanDesc->getPropertyDesc($i);
            $pt = null;
            $relationType = $this->beanAnnotationReader->getRelationType($pd);
            if ($relationType == S2Dao_RelationType::$belongTo) {
                if (!$this->relation) {
                    $rpt = $this->createRelationPropertyType($beanDesc, $pd, $dbMetaData);
                    $this->addRelationPropertyType($rpt);
                }
            } else if ($relationType != S2Dao_RelationType::$hasMany){
                $pt = $this->createPropertyType($beanDesc, $pd);
                $this->addPropertyType($pt);
            }
            if ($this->primaryKeys === null || count($this->primaryKeys) == 0) {
                $idAnnotation = $this->beanAnnotationReader->getId($pd);
                if ($idAnnotation != null) {
                    $this->primaryKeys = array($pt);
                    $pt->setPrimaryKey(true);
                }
            }
        }
    }
    
    protected function setupDatabaseMetaData(S2Container_BeanDesc $beanDesc, PDO $dbMetaData) {
        $this->setupPropertyPersistentAndColumnName($beanDesc, $dbMetaData);
        $this->setupPrimaryKey($dbMetaData);
    }
    
    protected function setupPrimaryKey(PDO $dbMetaData) {
        if ($this->primaryKeys === null || count($this->primaryKeys) == 0) {
            $pkeyList = new S2Dao_ArrayList();
            $primaryKeySet = S2Dao_DatabaseMetaDataUtil::getPrimaryKeySet($dbMetaData,
                                                                          $this->tableName);
            $size = $this->getPropertyTypeSize();
            for ($i = 0; $i < $size; ++$i) {
                $pt = $this->getPropertyType($i);
                if ($primaryKeySet->contains($pt->getColumnName())) {
                    $pt->setPrimaryKey(true);
                    $pkeyList->add($pt);
                } else {
                    $pt->setPrimaryKey(false);
                }
            }
            $this->primaryKeys = $pkeyList->toArray();
        }
    }
    
    protected function setupPropertyPersistentAndColumnName(S2Container_BeanDesc $beanDesc,
                                                            PDO $dbMetaData) {
        $columnSet = S2Dao_DatabaseMetaDataUtil::getColumnSet($dbMetaData, $this->tableName);
        if ($columnSet->isEmpty()) {
            self::$logger->warn('Table(' . $this->tableName . ') not found');
        }
        
        $cset = $columnSet->toArray();
        foreach($cset as $columnName){
            $col2 = str_replace('_', '', $columnName);
            $c = $this->getPropertyTypeSize();
            for($i = 0; $i < $c; ++$i){
                $pt = $this->getPropertyType($i);
                if(0 == strcasecmp($pt->getColumnName(), $col2)){
                    $pd = $pt->getPropertyDesc();
                    if ($this->beanAnnotationReader->getColumnAnnotation($pd) == null) {
                        $pt->setColumnName($columnName);
                    }
                    break;
                }
            }
        }
        
        $props = $this->beanAnnotationReader->getNoPersisteneProps();
        if ($props !== null) {
            $length = count($props);
            for ($i = 0; $i < $length; ++$i) {
                $pt = $this->getPropertyType(trim($props[$i]));
                $pt->setPersistent(false);
            }
        }
        
        $size = $this->getPropertyTypeSize();
        for ($i = 0; $i < $size; ++$i) {
            $pt = $this->getPropertyType($i);
            if (!$columnSet->contains($pt->getColumnName())) {
                $pt->setPersistent(false);
            }
        }
    }

    protected function setupPropertiesByColumnName() {
        $c = $this->getPropertyTypeSize();
        for ($i = 0; $i < $c; ++$i) {
            $pt = $this->getPropertyType($i);
            $this->propertyTypesByColumnName->put($pt->getColumnName(), $pt);
        }
    }
    
    protected function createRelationPropertyType(S2Container_BeanDesc $beanDesc,
                                                  S2Container_PropertyDesc $propertyDesc,
                                                  PDO $dbMetaData) {
        $myKeys = array();
        $yourKeys = array();
        $relno = 0;
        if($this->beanAnnotationReader->hasRelationNo($propertyDesc)){
            $relno = $this->beanAnnotationReader->getRelationNo($propertyDesc); 
        }else{
            $relno = $this->relationPropertyTypes->size();
        }
        $relkeys = $this->beanAnnotationReader->getRelationKey($propertyDesc);

        if($relkeys !== null){
            $st = preg_split('/[\s,]+/', $relkeys);
            $myKeyList = new S2Dao_ArrayList();
            $yourKeyList = new S2Dao_ArrayList();
            foreach ($st as $token) {
                $index = strpos($token, ':');
                if (0 < $index && $index !== false) {
                    $myKeyList->add(substr($token, 0, $index));
                    $yourKeyList->add(substr($token, $index + 1));
                } else {
                    $myKeyList->add($token);
                    $yourKeyList->add($token);
                }
            }
            $myKeys = $myKeyList->toArray();
            $yourKeys = $yourKeyList->toArray();
        }
        
        $beanClass = $propertyDesc->getPropertyType();
        $beanMetaData = new S2Dao_BeanMetaDataImpl();
        $beanMetaData->setBeanClass($beanClass);
        $beanMetaData->setDatabaseMetaData($dbMetaData);
        $beanMetaData->setAnnotationReaderFactory($this->getAnnotationReaderFactory());
        $beanMetaData->setValueTypeFactory($this->getValueTypeFactory());
        $beanMetaData->setRelation(true);
        $beanMetaData->initialize();
        return new S2Dao_RelationPropertyTypeImpl($propertyDesc,
                                                  $relno,
                                                  $myKeys,
                                                  $yourKeys,
                                                  $beanMetaData);
    }

    protected function addRelationPropertyType(S2Dao_RelationPropertyType $rpt) {
        $relNo = $rpt->getRelationNo();
        for ($i = $this->relationPropertyTypes->size(); $i <= $relNo; ++$i) {
            $this->relationPropertyTypes->add(null);
        }
        $this->relationPropertyTypes->set($relNo, $rpt);
    }
    
    private function addOneToManyRelationPropertyType(S2Dao_RelationPropertyType $rpt) {
        $this->oneToMenyRelationPropertyTypes->add($rpt);
    }

    /**
     * @see org.seasar.dao.BeanMetaData#getPrimaryKeySize()
     */
    public function getPrimaryKeySize() {
        return count($this->primaryKeys);
    }

    /**
     * @see org.seasar.dao.BeanMetaData#getPrimaryKey(int)
     */
    public function getPrimaryKey($index) {
        return $this->primaryKeys[$index]->getColumnName();
    }
    
    /**
     * @see org.seasar.dao.BeanMetaData#getAutoSelectList()
     */
    public function getAutoSelectList() {
        if ($this->autoSelectList != null) {
            return $this->autoSelectList;
        }
        $this->setupAutoSelectList();
        return $this->autoSelectList;
    }

    protected function setupAutoSelectList() {
        $buf = 'SELECT ';
        $ptSize = $this->getPropertyTypeSize();
        for ($i = 0; $i < $ptSize; ++$i) {
            $pt = $this->getPropertyType($i);
            if ($pt->isPersistent()) {
                $buf .= $this->tableName;
                $buf .= '.';
                $buf .= $pt->getColumnName();
                $buf .= ', ';
            }
        }
        
        $rptSize = $this->getRelationPropertyTypeSize();
        for ($i = 0; $i < $rptSize; ++$i) {
            $rpt = $this->getRelationPropertyType($i);
            $bmd = $rpt->getBeanMetaData();
            $bptSize = $bmd->getPropertyTypeSize();
            for ($j = 0; $j < $bptSize; ++$j) {
                $pt = $bmd->getPropertyType($j);
                if ($pt->isPersistent()) {
                    $columnName = $pt->getColumnName();
                    $buf .= $rpt->getPropertyName();
                    $buf .= '.';
                    $buf .= $columnName;
                    $buf .= ' AS ';
                    $buf .= $pt->getColumnName() . '_' . $rpt->getRelationNo();
                    $buf .= ', ';
                }
            }
        }
        $buf = preg_replace('/(, )$/', '', $buf);
        $this->autoSelectList = $buf;
    }

    /**
     * @see org.seasar.dao.BeanMetaData#isRelation()
     */
    public function isRelation() {
        return $this->relation;
    }

    public function setRelation($relation) {
        $this->relation = $relation;
    }

    public function setDatabaseMetaData(PDO $databaseMetaData) {
        $this->databaseMetaData = $databaseMetaData;
    }

    public function checkPrimaryKey() {
        if ($this->getPrimaryKeySize() == 0) {
            throw new S2Dao_PrimaryKeyNotFoundRuntimeException($this->getBeanClass());
        }
    }
    
    public function isBeanClassAssignable(ReflectionClass $clazz) {
        $beanClass = $this->getBeanClass();
        return $beanClass->isSubclassOf($clazz) || $clazz->isSubclassOf($beanClass);
    }
    
    public function getCode(){
        return self::$code;
    }
}
?>

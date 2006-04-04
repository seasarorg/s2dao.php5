<?php

/**
 * @author nowel
 */
class S2Dao_BeanMetaDataImpl extends S2Dao_DtoMetaDataImpl implements S2Dao_BeanMetaData {

    private static $logger_ = null;
    private $tableName_;
    private $propertyTypesByColumnName_ = null;
    private $relationPropertyTypes_ = null;
    private $primaryKeys_ = array();
    private $autoSelectList_;
    private $relation_;
    private $identifierGenerator_;
    private $versionNoPropertyName_ = 'versionNo';
    private $timestampPropertyName_ = 'timestamp';
    private $annotationReaderFactory_;
    
    private $casestr = 'strtoupper';

    public function __construct($beanClass,
                                $dbMetaData,
                                S2Dao_Dbms $dbms,
                                S2Dao_AnnotationReaderFactory $annotationReaderFactory = null,
                                $relation = null) {

        self::$logger_ = S2Container_S2Logger::getLogger(get_class($this));
        $this->propertyTypesByColumnName_ = new S2Dao_HashMap();
        $this->relationPropertyTypes_ = new S2Dao_ArrayList();
        
        if($annotationReaderFactory == null){
            $annotationReaderFactory = new S2Dao_FieldAnnotationReaderFactory();
        }

        if($relation == null){
            $relation = false;
        }
        
        $this->annotationReaderFactory_ = $annotationReaderFactory;
        $this->beanAnnotationReader_ =
                $annotationReaderFactory->createBeanAnnotationReader($beanClass);
        $this->setBeanClass($beanClass);
        $this->relation_ = $relation;
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($beanClass);
        $this->setupTableName($beanDesc);
        $this->setupVersionNoPropertyName($beanDesc);
        $this->setupTimestampPropertyName($beanDesc);
        $this->setupProperty($beanDesc, $dbMetaData, $dbms);
        $this->setupDatabaseMetaData($beanDesc, $dbMetaData, $dbms);
        $this->setupPropertiesByColumnName();
    }

    public function getTableName() {
        return $this->tableName_;
    }

    public function getVersionNoPropertyType(){
        return $this->getPropertyType($this->versionNoPropertyName_);
    }

    public function getTimestampPropertyType() {
        return $this->getPropertyType($this->timestampPropertyName_);
    }

    public function getVersionNoPropertyName() {
        return $this->versionNoPropertyName_;
    }

    public function getTimestampPropertyName() {
        return $this->timestampPropertyName_;
    }

    public function getPropertyTypeByColumnName($columnName) {
        $propertyType = $this->propertyTypesByColumnName_->get($columnName);
        if ($propertyType === null) {
            throw new S2Dao_ColumnNotFoundRuntimeException($this->tableName_, $columnName);
        }
        return $propertyType;
    }

    public function getPropertyTypeByAliasName($alias) {
        if ($this->hasPropertyTypeByColumnName($alias)) {
            return $this->getPropertyTypeByColumnName($alias);
        }
        $index = strrpos($alias, '_');
        if ($index < 0 || $index === false) {
            throw new S2Dao_ColumnNotFoundRuntimeException($this->tableName_, $alias);
        }
        $columnName = substr($alias, 0, $index);
        $relnoStr = substr($alias, $index + 1);
        $relno = -1;
        try {
            $relno = (int)$relnoStr;
        } catch (Exception $t) {
            throw new S2Dao_ColumnNotFoundRuntimeException($this->tableName_, $alias);
        }
        $rpt = $this->getRelationPropertyType($relno);
        if (!$rpt->getBeanMetaData()->hasPropertyTypeByColumnName($columnName)){
            throw new S2Dao_ColumnNotFoundRuntimeException($this->tableName_, $alias);
        }
        return $rpt->getBeanMetaData()->getPropertyTypeByColumnName($columnName);
    }

    public function hasPropertyTypeByColumnName($columnName) {
        $upperColumnName = strtoupper($columnName);
        $lowerColumnName = strtolower($columnName);
        return $this->propertyTypesByColumnName_->get($upperColumnName) != null ||
                $this->propertyTypesByColumnName_->get($lowerColumnName) != null;
    }

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
        $relno = -1;
        try {
            $relno = (int)$relnoStr;
        } catch (Exception $t) {
            return false;
        }
        if ($relno >= $this->getRelationPropertyTypeSize()) {
            return false;
        }
        $rpt = $this->getRelationPropertyType($relno);
        return $rpt->getBeanMetaData()->hasPropertyTypeByColumnName($columnName);
    }

    public function hasVersionNoPropertyType() {
        return $this->hasPropertyType($this->versionNoPropertyName_);
    }

    public function hasTimestampPropertyType() {
        return $this->hasPropertyType($this->timestampPropertyName_);
    }

    public function convertFullColumnName($alias) {
        if ($this->hasPropertyTypeByColumnName($alias)) {
            return $this->tableName_ . '.' . $alias;
        }
        $index = strrpos($alias, '_');
        if ($index < 0 || $index === false) {
            throw new S2Dao_ColumnNotFoundRuntimeException($this->tableName_, $alias);
        }
        $columnName = substr($alias, 0, $index);
        $relnoStr = substr($alias, $index + 1);
        $relno = -1;
        try {
            $relno = (int)$relnoStr;
        } catch (Exception $t) {
            throw new S2Dao_ColumnNotFoundRuntimeException($this->tableName_, $alias);
        }
        $rpt = $this->getRelationPropertyType($relno);
        if (!$rpt->getBeanMetaData()->hasPropertyTypeByColumnName($columnName)) {
            throw new S2Dao_ColumnNotFoundRuntimeException($this->tableName_, $alias);
        }
        return $rpt->getPropertyName() . '.' . $columnName;
    }

    public function getRelationPropertyTypeSize() {
        return $this->relationPropertyTypes_->size();
    }

    public function getRelationPropertyType($index) {
        if(is_integer($index)){
          return $this->relationPropertyTypes_->get($index);
        } else {
            for ($i = 0; $i < $this->getRelationPropertyTypeSize(); $i++) {
                $rpt = $this->relationPropertyTypes_->get($i);
                if ($rpt != null &&
                    strcasecmp($rpt->getPropertyName(), $index) == 0){
                    return $rpt;
                }
            }
            throw new S2Container_PropertyNotFoundRuntimeException($this->getBeanClass(),
                                                                   $index);
        }
    }

    protected function setupTableName(S2Container_BeanDesc $beanDesc) {
        $ta = $this->beanAnnotationReader_->getTableAnnotation();
        if ($ta != null) {
            $this->tableName_ = $ta;
        } else {
            $this->tableName_ = $this->getBeanClass()->getName();
        }
    }

    protected function setupVersionNoPropertyName(S2Container_BeanDesc $beanDesc) {
        $vna = $this->beanAnnotationReader_->getVersionNoPropertyNameAnnotation();
        if ($vna !== null) {
            $this->versionNoPropertyName_ = $vna;
        }
    }

    protected function setupTimestampPropertyName(S2Container_BeanDesc $beanDesc) {
        $tsa = $this->beanAnnotationReader_->getTimestampPropertyName();
        if ($tsa !== null) {
            $this->timestampPropertyName_ = $tsa;
        }
    }

    protected function setupProperty(S2Container_BeanDesc $beanDesc,
                                   $dbMetaData,
                                   S2Dao_Dbms $dbms) {

        for ($i = 0; $i < $beanDesc->getPropertyDescSize(); ++$i) {
            $pd = $beanDesc->getPropertyDesc($i);
            $pt = null;
            if($this->beanAnnotationReader_->hasRelationNo($pd)){
                if (!$this->relation_) {
                    $rpt = $this->createRelationPropertyType(
                                         $beanDesc, $pd, $dbMetaData, $dbms);
                    $this->addRelationPropertyType($rpt);
                }
            } else {
                $pt = $this->createPropertyType($beanDesc, $pd);
                $this->addPropertyType($pt);
            }
            if ($this->identifierGenerator_ == null && $pt !== null) {
                $idAnnotation = $this->beanAnnotationReader_->getId($pd);
                if($idAnnotation != null){
                    $this->identifierGenerator_ =
                        S2Dao_IdentifierGeneratorFactory::createIdentifierGenerator(
                                    $pd->getPropertyName(), $dbms, $idAnnotation
                                );
                    $this->primaryKeys_ = (array)$pt->getColumnName();
                    $pt->setPrimaryKey(true);
                }
            }
        }
    }

    protected function setupDatabaseMetaData(S2Container_BeanDesc $beanDesc,
                                           $dbMetaData,
                                           S2Dao_Dbms $dbms) {
        $this->setupPropertyPersistentAndColumnName($beanDesc, $dbMetaData);
        $this->setupPrimaryKey($dbMetaData, $dbms);
    }

    protected function setupPrimaryKey($dbMetaData, S2Dao_Dbms $dbms) {
        if ($this->identifierGenerator_ == null) {
            $pkeyList = new S2Dao_ArrayList();
            $primaryKeySet = S2Dao_DatabaseMetaDataUtil::getPrimaryKeySet(
                                               $dbMetaData, $this->tableName_);

            //$c = $primaryKeySet->size();
            $c = $this->getPropertyTypeSize();
            for ($i = 0; $i < $c; ++$i) {
                $pt = $this->getPropertyType($i);
                if ($primaryKeySet->contains($pt->getColumnName())) {
                    $pt->setPrimaryKey(true);
                    $pkeyList->add($pt->getColumnName());
                } else {
                    $pt->setPrimaryKey(false);
                }
            }
            $this->primaryKeys_ = $pkeyList->toArray();
            $this->identifierGenerator_ =
                S2Dao_IdentifierGeneratorFactory::createIdentifierGenerator(null, $dbms);
        }
    }

    protected function setupPropertyPersistentAndColumnName(
                                            S2Container_BeanDesc $beanDesc,
                                            $dbMetaData) {
        
        $columnSet = S2Dao_DatabaseMetaDataUtil::getColumnSet($dbMetaData, $this->tableName_);
        if ($columnSet->isEmpty()) {
            self::$logger_->log('WDAO0002', (array)$this->tableName_);
        }
        
        $cset = $columnSet->toArray();
        foreach($cset as $columnName){
            $col2 = str_replace('_', '', $columnName);
            for($i = 0; $i < $this->getPropertyTypeSize(); ++$i){
                $pt = $this->getPropertyType($i);
                if(0 == strcasecmp($pt->getColumnName(), $col2)){
                    $pt->setColumnName($columnName);
                    break;
                }
            }
        }

        $props = $this->beanAnnotationReader_->getNoPersisteneProps();
        if ($props != null) {
            $length = count($props);
            for ($i = 0; $i < $length; ++$i) {
                $pt = $this->getPropertyType(trim($props[$i]));
                $pt->setPersistent(false);
            }
        }
        for ($i = 0; $i < $this->getPropertyTypeSize(); ++$i) {
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
            $columnName = strtoupper($pt->getColumnName());
            $this->propertyTypesByColumnName_->put($columnName, $pt);
        }
    }

    protected function createRelationPropertyType(S2Container_BeanDesc $beanDesc,
                                                  S2Container_PropertyDesc $propertyDesc,
                                                  $dbMetaData,
                                                  S2Dao_Dbms $dbms) {
        $myKeys = array();
        $yourKeys = array();
        $relno = $this->beanAnnotationReader_->getRelationNo($propertyDesc);
        $relkeys = $this->beanAnnotationReader_->getRelationKey($propertyDesc);

        if($relkeys !== null){
            $delim = new ArrayObject(preg_split('/[\s,]+/', $relkeys));
            $st = $delim->getIterator();
            $myKeyList = new S2Dao_ArrayList();
            $yourKeyList = new S2Dao_ArrayList();
            while ($st->valid()) {
                $token = $st->current();
                $index = strpos($token, ':');
                if ($index !== false && $index > 0) {
                    $myKeyList->add(substr($token, 0, $index));
                    $yourKeyList->add(substr($token, $index + 1));
                } else {
                    $myKeyList->add($token);
                    $yourKeyList->add($token);
                }
                $st->next();
            }
            $myKeys = $myKeyList->toArray();
            $yourKeys = $yourKeyList->toArray();
        }
        $beanClass = $propertyDesc->getPropertyType();
        if($beanClass === null){
            $beanClass = $beanDesc->getBeanClass();
        }
        return new S2Dao_RelationPropertyTypeImpl($propertyDesc,
                    $relno, $myKeys, $yourKeys,
                    new S2Dao_BeanMetaDataImpl($beanClass, $dbMetaData, $dbms,
                                         $this->annotationReaderFactory_, true)
                );
    }

    protected function addRelationPropertyType(S2Dao_RelationPropertyType $rpt) {
        $relno = $rpt->getRelationNo();
        for ($i = $this->relationPropertyTypes_->size(); $i <= $relno; ++$i) {
            $this->relationPropertyTypes_->add(null);
        }
        $this->relationPropertyTypes_->set($rpt->getRelationNo(), $rpt);
    }

    public function getPrimaryKeySize() {
        return count($this->primaryKeys_);
    }

    public function getPrimaryKey($index) {
        return $this->primaryKeys_[$index];
    }

    public function getIdentifierGenerator() {
        return $this->identifierGenerator_;
    }

    public function getAutoSelectList() {
        if ($this->autoSelectList_ != null) {
            return $this->autoSelectList_;
        }
        $this->setupAutoSelectList();
        return $this->autoSelectList_;
    }

    protected function setupAutoSelectList() {
        $buf = '';
        $buf .= 'SELECT ';
        for ($i = 0; $i < $this->getPropertyTypeSize(); ++$i) {
            $pt = $this->getPropertyType($i);
            if ($pt !== null && $pt->isPersistent()) {
                $buf .= $this->tableName_;
                $buf .= '.';
                $buf .= $pt->getColumnName();
                $buf .= ', ';
            }
        }
        for ($i = 0; $i < $this->getRelationPropertyTypeSize(); ++$i) {
            $rpt = $this->getRelationPropertyType($i);
            $bmd = $rpt->getBeanMetaData();
            for ($j = 0; $j < $bmd->getPropertyTypeSize(); ++$j) {
                $pt = $bmd->getPropertyType($j);
                if ($pt !== null && $pt->isPersistent()) {
                    $columnName = $pt->getColumnName();
                    //$tableName = $rpt->getBeanMetaData()->getTableName();
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
        $this->autoSelectList_ = $buf;
    }

    public function isRelation() {
        return $this->relation_;
    }
}
?>

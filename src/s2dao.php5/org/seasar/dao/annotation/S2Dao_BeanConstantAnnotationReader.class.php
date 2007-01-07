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
class S2Dao_BeanConstantAnnotationReader implements S2Dao_BeanAnnotationReader {
    
    const TABLE = 'TABLE';
    const RELNO_SUFFIX = '_RELNO';
    const RELBEAN_SUFFIX = '_RELBEAN';
    const RELTABLE_SUFFIX = '_RELTABLE';
    const RELTYPE_SUFFIX = '_RELTYPE';
    const RELKEYS_SUFFIX = '_RELKEYS';
    const ID_SUFFIX = '_ID';
    const NO_PERSISTENT_PROPS = 'NO_PERSISTENT_PROPS';
    const VERSION_NO_PROPERTY = 'VERSION_NO_PROPERTY';
    const TIMESTAMP_PROPERTY = 'TIMESTAMP_PROPERTY';
    const COLUMN_SUFFIX = '_COLUMN';
    const VALUE_TYPE_SUFFIX = '_VALUE_TYPE';

    private $beanDesc;
    
    public function __construct(S2Container_BeanDesc $beanDesc) {
        $this->beanDesc = $beanDesc;
    }

    public function getColumnAnnotation(S2Container_PropertyDesc $pd) {
        $propertyName = $pd->getPropertyName();
        $columnNameKey = $propertyName . self::COLUMN_SUFFIX;
        $columnName = $propertyName;
        if ($this->beanDesc->hasConstant($columnNameKey)) {
            $columnName = $this->beanDesc->getConstant($columnNameKey);
        }
        return $columnName;
    }

    public function getTableAnnotation() {
        if ($this->beanDesc->hasConstant(self::TABLE)) {
            return $this->beanDesc->getConstant(self::TABLE);
        }
        return null;
    }

    public function getVersionNoPropertyNameAnnotation() {
        if ($this->beanDesc->hasConstant(self::VERSION_NO_PROPERTY)) {
            return $this->beanDesc->getConstant(self::VERSION_NO_PROPERTY);
        }
        return null;
    }

    public function getTimestampPropertyName() {
        if ($this->beanDesc->hasConstant(self::TIMESTAMP_PROPERTY)) {
            return $this->beanDesc->getConstant(self::TIMESTAMP_PROPERTY);
        }
        return null;
    }

    public function getId(S2Container_PropertyDesc $pd) {
        $idKey = $pd->getPropertyName() . self::ID_SUFFIX;
        if ($this->beanDesc->hasConstant($idKey)) {
            return $this->beanDesc->getConstant($idKey);
        }
        return null;
    }

    public function getNoPersisteneProps() {
        if ($this->beanDesc->hasConstant(self::NO_PERSISTENT_PROPS)) {
            $s = $this->beanDesc->getConstant(self::NO_PERSISTENT_PROPS);
            return S2Dao_ArrayUtil::spacetrim(explode(',', $s));
        }
        return null;
    }

    public function getRelationKey(S2Container_PropertyDesc $pd) {
        $propertyName = $pd->getPropertyName();
        $relkeysKey = $propertyName . self::RELKEYS_SUFFIX;
        if($this->beanDesc->hasConstant($relkeysKey)) {
            return $this->beanDesc->getConstant($relkeysKey);
        }
        return null;
    }
    
    public function getRelationNo(S2Container_PropertyDesc $pd) {
        $relnoKey = $pd->getPropertyName() . self::RELNO_SUFFIX;
        return (int)$this->beanDesc->getConstant($relnoKey);
    }

    public function hasRelationNo(S2Container_PropertyDesc $pd) {
        $relnoKey = $pd->getPropertyName() . self::RELNO_SUFFIX;
        return $this->beanDesc->hasConstant($relnoKey);
    }
    
    public function getRelationTable(S2Container_PropertyDesc $pd) {
        $key = $pd->getPropertyName() . self::RELTABLE_SUFFIX;
        if ($this->beanDesc->hasConstant($key)) {
            return $this->beanDesc->getConstant($key);
        }
        return null;
    }
    
    public function getRelationType(S2Container_PropertyDesc $pd) {
        $reltypeKey = $pd->getPropertyName() . self::RELTYPE_SUFFIX;
        if ($this->beanDesc->hasConstant($reltypeKey)) {
            return $this->beanDesc->getConstant($reltypeKey);
        }
        return null;
    }
    
    public function getRelationBean(S2Container_PropertyDesc $pd) {
        $key = $pd->getPropertyName() . self::RELBEAN_SUFFIX;
        if ($this->beanDesc->hasConstant($key)) {
            return $this->beanDesc->getConstant($key);
        }
        return null;
    }
    
    public function getValueType(S2Container_PropertyDesc $pd) {
        $valueTypeKey = $pd->getPropertyName() . self::VALUE_TYPE_SUFFIX;
        if ($this->beanDesc->hasConstant($valueTypeKey)) {
            return $this->beanDesc->getConstant($valueTypeKey);
        }
        return null;
    }
}

?>
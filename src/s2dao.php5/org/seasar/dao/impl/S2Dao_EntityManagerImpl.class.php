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
class S2Dao_EntityManagerImpl implements S2Dao_EntityManager {
    
    private static $EMPTY_ARGS = array();

    private $beanMetaData;
    
    private $daoMetaData;
    
    private $sqlCommandFactory;

    public function __construct(
            S2Dao_DaoMetaData $daoMetaData,
            S2Dao_BeanMetaData $beanMetaData,
            S2Dao_SqlCommandFactory $sqlCommandFactory) {
        $this->daoMetaData = $daoMetaData;
        $this->beanMetaData = $beanMetaData;
        $this->sqlCommandFactory = $sqlCommandFactory;
    }

    /**
     * @return DaoMetaData
     */
    public function getDaoMetaData() {
        return $this->daoMetaData;
    }

    /**
     * @see org.seasar.dao.EntityManager#find
     * @return List
     */
    public function find($query, $arg1 = null, $arg2 = null, $arg3 = null) {
        switch(func_num_args()){
        case 1:
            return $this->find($query, self::$EMPTY_ARGS);
        case 2:
            if(is_array($arg1) && !is_string($arg1)){
                return $this->find0($query, $arg1);
            }
            return $this->find($query, array($arg1));
        case 3:
            return $this->find($query, array($arg1, $arg2));
        case 4:
            return $this->find($query, array($arg1, $arg2, $arg3));
        }
    }

    /**
     * @return List
     */
    public function find0($query, array $args) {
        $cmd = $this->sqlCommandFactory->createSelectDynamicCommandByQuery(
                $this->daoMetaData->getDbms(),
                $this->beanMetaData,
                new ReflectionClass('S2Dao_ArrayList'),
                $query);
        return $cmd->execute($args);
    }

    /**
     * @see org.seasar.dao.EntityManager#findArray
     */
    public function findArray($query, $arg1 = null, $arg2 = null, $arg3 = null) {
        switch(func_num_args()){
        case 1:
            return $this->findArray($query, self::$EMPTY_ARGS);
        case 2:
            if(is_array($arg1) && !is_string($arg1)){
                return $this->findArray0($query, $arg1);
            }
            return $this->findArray($query, array($arg1));
        case 3:
            return $this->findArray($query, array($arg1, $arg2));
        case 4:
            return $this->findArray($query, array($arg1, $arg2, $arg3));
        }
    }

    public function findArray0($query, array $args) {
        $returnClass = $this->beanMetaData->getBeanClass()->getClass();
        $cmd = $this->sqlCommandFactory->createSelectDynamicCommandByQuery(
                            $daoMetaData->getDbms(),
                            $this->beanMetaData,
                            $returnClass,
                            $query);
        return $cmd->execute($args);
    }

    /**
     * @see org.seasar.dao.EntityManager#findBean
     */
    public function findBean($query, $arg1 = null, $arg2 = null, $arg3 = null) {
        switch(func_num_args()){
        case 1:
            return $this->findBean($query, self::$EMPTY_ARGS);
        case 2:
            if(is_array($arg1) && !is_string($arg1)){
                return $this->findBean0($query, $arg1);
            }
            return $this->findBean($query, array($arg1));
        case 3:
            return $this->findBean($query, array($arg1, $arg2));
        case 4:
            return $this->findBean($query, array($arg1, $arg2, $arg3));
        }
    }

    /**
     * @see org.seasar.dao.EntityManager#findBean
     */
    public function findBean0($query, array $args) {
        $cmd = $this->sqlCommandFactory->createSelectDynamicCommandByQuery(
                    $this->daoMetaData->getDbms(),
                    $this->beanMetaData,
                    $this->beanMetaData->getBeanClass(),
                    $query);
        return $cmd->execute($args);
    }

    /**
     * @see org.seasar.dao.EntityManager#findObject
     */
    public function findObject($query, $arg1 = null, $arg2 = null, $arg3 = null) {
        switch(func_num_args()){
        case 1:
            return $this->findObject($query, self::$EMPTY_ARGS);
        case 2:
            if(is_array($arg1) && !is_string($arg1)){
                $this->findObject0($query, $arg1);
            }
            return $this->findObject($query, array($arg1));
        case 3:
            return $this->findObject($query, array($arg1, $arg2));
        case 4:
            return $this->findObject($query, array($arg1, $arg2, $arg3));
        }
    }

    /**
     * @see org.seasar.dao.EntityManager#findObject
     */
    public function findObject0($query, array $args) {
        $cmd = $this->sqlCommandFactory->createSelectDynamicCommand(
                    new S2Dao_ObjectResultSetHandler(),
                    $query);
        return $cmd->execute($args);
    }
    
    /**
     * @see org.seasar.dao.EntityManager#findYaml
     */
    public function findYaml($query, $arg1 = null, $arg2 = null, $arg3 = null) {
       switch(func_num_args()){
            case 1:
                return $this->findYaml($query, self::$EMPTY_ARGS);
            case 2:
                if(is_array($arg1) && !is_string($arg1)){
                    return $this->findYaml0($query, $arg1);
                }
                return $this->findYaml($query, array($arg1));
            case 3:
                return $this->findYaml($query, array($arg1, $arg2));
            case 4:
                return $this->findYaml($query, array($arg1, $arg2, $arg3));
        }
    }
    
    public function findYaml0($query, array $args){
        $cmd = $this->sqlCommandFactory->createSelectDynamicCommand(
                    new S2Dao_BeanYamlMetaDataResultSetHandler(),
                    $query);
        return $cmd->execute($args);
    }
    
    /**
     * @see org.seasar.dao.EntityManager#findJson
     */
    public function findJson($query, $arg1 = null, $arg2 = null, $arg3 = null) {
       switch(func_num_args()){
            case 1:
                return $this->findJson($query, self::$EMPTY_ARGS);
            case 2:
                if(is_array($arg1) && !is_string($arg1)){
                    return $this->findJson0($query, $arg1);
                }
                return $this->findJson($query, array($arg1));
            case 3:
                return $this->findJson($query, array($arg1, $arg2));
            case 4:
                return $this->findJson($query, array($arg1, $arg2, $arg3));
        }
    }
    
    public function findJson0($query, array $args){
        $cmd = $this->sqlCommandFactory->createSelectDynamicCommand(
                    new S2Dao_BeanJsonMetaDataResultSetHandler(),
                    $query);
        return $cmd->execute($args);
    }

}
?>

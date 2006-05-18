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
// $Id$
//
/**
 * @author nowel
 */
class S2Dao_EntityManagerImpl implements S2Dao_EntityManager {

    private static $EMPTY_ARGS = array();

    private $daoMetaData_;

    public function __construct(S2Dao_DaoMetaData $daoMetaData) {
        $this->daoMetaData_ = $daoMetaData;
    }

    public function getDaoMetaData() {
        return $this->daoMetaData_;
    }

    public function find($query, $args = null, $arg2 = null, $arg3 = null) {
        switch(count(func_get_args())){
            case 1:
                return $this->find($query, self::$EMPTY_ARGS);
            case 2:
                if(is_array($args)){
                    $cmd = $this->daoMetaData_->createFindCommand($query);
                    return $cmd->execute($args);
                } else {
                    return $this->find($query, array($args));
                }
            case 3:
                return $this->find($query, array($args, $arg2));
            case 4:
                return $this->find($query, array($args, $arg2, $arg3));
        }
    }

    public function findArray($query, $args = null, $arg2 = null, $arg3 = null) {
        switch(count(func_get_args())){
            case 1:
                return $this->findArray($query, self::$EMPTY_ARGS);
            case 2:
                if(is_array($args)){
                    $cmd = $this->daoMetaData_->createFindArrayCommand($query);
                    return $cmd->execute($args);
                } else {
                    return $this->findArray($query, array($args));
                }
            case 3:
                return $this->findArray($query, array($args, $arg2));
            case 4:
                return $this->findArray($query, array($args, $arg2, $arg3));
        }
    }
    
    public function findBean($query, $args = null, $arg2 = null, $arg3 = null) {
        switch(func_num_args()){
            case 1:
                return $this->findBean($query, self::$EMPTY_ARGS);
            case 2:
                if(is_array($args)){
                    $cmd = $this->daoMetaData_->createFindBeanCommand($query);
                    return $cmd->execute($args);
                } else {
                    return $this->findBean($query, array($args));
                }
            case 3:
                return $this->findBean($query, array($args, $arg2));
            case 4:
                return $this->findBean($query, array($args, $arg2, $arg3));
        }
    }

    public function findObject($query, $args = null, $arg2 = null, $arg3 = null) {
        switch(func_num_args()){
            case 1:
                return $this->findObject($query, self::$EMPTY_ARGS);
            case 2:
                if(is_array($args)){
                    $cmd = $this->daoMetaData_->createFindObjectCommand($query);
                    return $cmd->execute($args);
                } else {
                    return $this->findObject($query, array($args));
                }
            case 3:
                return $this->findObject($query, array($args, $arg2));
            case 4:
                return $this->findObject($query, array($args, $arg2, $arg3));
        }
    }
}
?>

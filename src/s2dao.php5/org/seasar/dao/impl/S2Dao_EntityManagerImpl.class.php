<?php

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
        switch( count(func_get_args()) ){
            case 1:
                return $this->find($query, self::$EMPTY_ARGS);
            case 2:
                if( is_array($args) ){
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
        switch( count(func_get_args()) ){
            case 1:
                return $this->findArray($query, self::$EMPTY_ARGS);
            case 2:
                if( is_array($args) ){
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
        switch( count(func_get_args()) ){
            case 1:
                return $this->findBean($query, self::$EMPTY_ARGS);
            case 2:
                if( is_array($args) ){
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
        switch( count(func_get_args()) ){
            case 1:
                return $this->findObject($query, self::$EMPTY_ARGS);
            case 2:
                if( is_array($args) ){
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

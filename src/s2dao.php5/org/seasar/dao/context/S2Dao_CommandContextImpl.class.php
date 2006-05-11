<?php

/**
 * @author nowel
 */
class S2Dao_CommandContextImpl implements S2Dao_CommandContext {

    private static $logger_ = null;
    private $args_;
    private $argTypes_;
    private $sqlBuf_ = '';
    private $bindVariables_;
    private $bindVariableTypes_;
    private $enabled_ = true;
    private $parent_;

    public function __construct($parent = null){
        if(self::$logger_ === null){
            self::$logger_ = S2Container_S2Logger::getLogger(get_class($this));
        }
        $this->args_ = new S2Dao_HashMap();
        $this->argTypes_ = new S2Dao_HashMap();
        $this->sqlBuf_ = '';
        $this->bindVariables_ = new S2Dao_ArrayList();
        $this->bindVariableTypes_ = new S2Dao_ArrayList();
        $this->parent_ = $parent;
        $this->enabled_ = false;
    }

    public function getArg($name) {
        if ($this->args_->containsKey($name)) {
            return $this->args_->get($name);
        } else if ($this->parent_ !== null) {
            return $this->parent_->getArg($name);
        } else {
            if ($this->args_->size() == 1) {
                return $this->args_->get(0);
            }
            self::$logger_->info('Argument(' . $name . ') not found');
            return null;
        }
    }

    public function getArgType($name) {
        if ($this->argTypes_->containsKey($name)) {
            return $this->argTypes_->get($name);
        } else if ($this->parent_ != null) {
            return $this->parent_->getArgType($name);
        } else {
            if ($this->argTypes_->size() == 1) {
                return $this->argTypes_->get(0);
            }
            self::$logger_->info('Argument(' . $name . ') not found');
            return null;
        }
    }

    public function addArg($name, $arg, $argType) {
        $this->args_->put($name, $arg);
        $this->argTypes_->put($name, $argType);
    }

    public function getSql() {
        return (string)$this->sqlBuf_;
    }

    public function getBindVariables() {
        return $this->bindVariables_->toArray($this->bindVariables_->size());
    }

    public function getBindVariableTypes() {
        return $this->bindVariableTypes_->toArray($this->bindVariableTypes_->size());
    }

    public function addSql($sql, $bindVariable = null, $bindVariableType = null) {
        if(is_array($bindVariable) && is_array($bindVariableType)){
            $this->sqlBuf_ .= $sql;
            $c = count($bindVariable);
            for ($i = 0; $i < $c; ++$i) {
                $this->bindVariables_->add($bindVariable[$i]);
                $this->bindVariableTypes_->add($bindVariableType[$i]);
            }
            return $this;
        } else if($bindVariable === null && $bindVariableType === null){
            $this->sqlBuf_ .= $sql;
            return $this;
        } else {
            $this->sqlBuf_ .= $sql;
            $this->bindVariables_->add($bindVariable);
            $this->bindVariableTypes_->add($bindVariableType);
            return $this;
        }
    }

    public function isEnabled() {
        return $this->enabled_;
    }

    public function setEnabled($enabled) {
        $this->enabled_ = $enabled;
    }
}
?>

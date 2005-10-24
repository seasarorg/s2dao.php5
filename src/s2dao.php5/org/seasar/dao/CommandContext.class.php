<?php

/**
 * @author nowel
 */
interface CommandContext {

    public function getArg($name);

    public function getArgType($name);

    public function addArg($name, $arg, $argType);

    public function getSql();

    public function getBindVariables();

    public function getBindVariableTypes();

    //public function addSql($sql);

    public function addSql($sql, $bindVariable = null, $bindVariableType = null);

    public function isEnabled();

    public function setEnabled($enabled);
}
?>

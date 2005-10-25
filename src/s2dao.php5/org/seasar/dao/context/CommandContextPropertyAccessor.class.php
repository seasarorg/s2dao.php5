<?php

/**
 * @author nowel
 */
class CommandContextPropertyAccessor extends ObjectPropertyAccessor {

    public function getProperty($cx, $target, $name){
        try {
            $ctx = $target;
            $argName = $name->toString();
            return $ctx->getArg($argName);
        } catch (OgnlException $e ){
            throw new OgnlException($e);
        }
    }
}
?>

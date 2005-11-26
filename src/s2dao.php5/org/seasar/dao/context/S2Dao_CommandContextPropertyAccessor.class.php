<?php

/**
 * @author nowel
 */
class S2Dao_CommandContextPropertyAccessor extends ObjectPropertyAccessor {

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

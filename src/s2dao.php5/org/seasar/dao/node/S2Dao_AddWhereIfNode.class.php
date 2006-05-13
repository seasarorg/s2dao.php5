<?php

/**
 * @author nowel
 */
class S2Dao_AddWhereIfNode extends S2Dao_ContainerNode {
    
    const pat = '/\s*(order\sby)|$)/i';
    
    public function __construct() {
    }
    
    public function accept(S2Dao_CommandContext $ctx) {
        $childCtx = new S2Dao_CommandContextImpl($ctx);
        parent::accept($childCtx);
        if ($childCtx->isEnabled()) {
            $sql = $childCtx->getSql();
            if(!preg_match(self::pat, $sql)){
                $sql .= ' WHERE ' . $sql;
            }
            $ctx->addSql($sql, $childCtx->getBindVariables(), $childCtx->getBindVariableTypes());
        }
    }
}

?>
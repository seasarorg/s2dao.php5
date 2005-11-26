<?php

/**
 * @author nowel
 */
interface S2Dao_Node {
    public function getChildSize();
    public function getChild($index);
    public function addChild($node);
    public function accept(S2Dao_CommandContext $ctx);
}
?>

<?php

/**
 * @author nowel
 */
class S2Dao_ContainerNode extends S2Dao_AbstractNode {

    public function __construct() {
    }

    public function accept(S2Dao_CommandContext $ctx) {
        for ($i = 0; $i < $this->getChildSize(); ++$i) {
            $this->getChild($i)->accept($ctx);
        }
    }
}
?>

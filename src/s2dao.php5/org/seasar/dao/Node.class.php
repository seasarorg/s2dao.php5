<?php

/**
 * @author nowel
 */
interface Node {

    public function getChildSize();

    public function getChild($index);

    public function addChild($node);

    public function accept(CommandContext $ctx);

}

?>

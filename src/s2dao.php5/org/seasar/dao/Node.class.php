<?php

/**
 * @author Yusuke Hata
 */
interface Node {

    public function getChildSize();

    public function getChild($index);

    public function addChild($node);

    public function accept(CommandContext $ctx);

}

?>

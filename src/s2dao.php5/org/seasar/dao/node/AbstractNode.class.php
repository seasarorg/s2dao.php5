<?php

/**
 * @author nowel
 */
abstract class AbstractNode implements Node {

    private $children_ = array();
    
    public function __construct() {
    }

    public function getChildSize() {
        return count($this->children_);
    }
    
    public function getChild($index) {
        return $this->children_[$index];
    }
    
    public function addChild($node) {
        $this->children_[] = $node;
    }
}
?>

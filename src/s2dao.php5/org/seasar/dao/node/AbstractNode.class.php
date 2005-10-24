<?php

/**
 * @author nowel
 */
abstract class AbstractNode implements Node {

    private $children_ = null;
    
    public function __construct() {
    }

    public function getChildSize() {
        //return $this->children_->size();
        return count($this->children_);
    }
    
    public function getChild($index) {
        //return $this->children_->get($index);
        return $this->children_[$index];
    }
    
    public function addChild($node) {
        //$this->children_->add($node);
        $this->children_[] = $node;
    }
}
?>

<?php

class Ddd extends Ccc {
    const NO_PERSISTENT_PROPS = "";

    private $name;

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}

?>
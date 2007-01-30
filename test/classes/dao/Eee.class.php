<?php

class Eee extends Ccc {
    const NO_PERSISTENT_PROPS = "name";

    private $name;

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}


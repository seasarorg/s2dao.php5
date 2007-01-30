<?php

class Foo {

    const bbb_ID = "assigned";
    private $bbb;

    public function getBbb() {
        return $this->bbb;
    }

    public function setBbb($bbb) {
        $this->bbb = $bbb;
    }
}
?>
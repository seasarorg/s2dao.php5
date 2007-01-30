<?php

class Hoge {

    const foo_RELNO = 0;
    const aaa_ID = "assigned";
    private $aaa;
    private $foo;

    public function getAaa() {
        return $this->aaa;
    }

    public function setAaa($aaa) {
        $this->aaa = $aaa;
    }

    public function getFoo() {
        return $this->foo;
    }

    public function setFoo(Foo $foo) {
        $this->foo = $foo;
    }
}
?>
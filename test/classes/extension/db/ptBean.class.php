<?php

class ptBean {
    
    private $aaa;
    private $bbb;
    private $ccc;
    private $hoge;
    private $foo;
    private $bar;
    private $baz;
    
    public function setAaa($a){
        $this->aaa = $a;
    }
    
    public function setBbb($b){
        $this->bbb = $b;
    }
    
    public function setCcc($c){
        $this->ccc = $c;
    }
    
    public function setHoge(ptHoge $hoge){
        $this->hoge = $hoge;
    }
    
    public function setFoo(ptFoo $foo){
        $this->foo = $foo;
    }
    
    public function setBar(ptBar $bar){
        $this->bar = $bar;
    }
    
    public function setBaz($baz){
        $this->baz = $baz;
    }
    
    public function getAaa(){
        return $this->aaa;
    }
    
    public function getBbb(){
        return $this->bbb;
    }
    
    public function getCcc(){
        return $this->ccc;
    }
    
    public function getHoge(){
        return $this->hoge;
    }
    
    public function getFoo(){
        return $this->foo;
    }
    
    public function getBar(){
        return $this->bar;
    }
    
    public function getBaz(){
        return $this->baz;
    }
}

?>
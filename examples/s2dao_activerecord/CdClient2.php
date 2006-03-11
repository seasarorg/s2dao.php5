<?php
require_once dirname(__FILE__) . "/example.inc.php";
require dirname(__FILE__) . "/classes/cd.class.php";

$container = S2ContainerFactory::create("./resource/cd.dicon.xml");
$cd = $container->getComponent("Cd");

$cd->id = 1;
var_dump($cd->find()->toString());

var_dump('count: ' . $cd->count());

if(1 < $cd->count()){
    unset($cd->id);
    
    echo '###### no order ######' . PHP_EOL;
    $iter = $cd->findAll();
    for($iter->rewind(); $iter->valid(); $iter->next()){
        var_dump($iter->current()->toString());
    }
    
    echo '###### set order ######' . PHP_EOL;
    $iter = $cd->findAll(null, array('id' => 'ASC', 'title' => 'DESC'));
    for($iter->rewind(); $iter->valid(); $iter->next()){
        var_dump($iter->current()->toString());
    }
}

?>

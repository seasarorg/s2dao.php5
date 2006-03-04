<?php

class cd {}

require_once dirname(__FILE__) . "/example.inc.php";
$container = S2ContainerFactory::create("./resource/cd.dicon.xml");
$cd = $container->getComponent("CD");

var_dump($cd->findAll());

?>

<?php
require_once dirname(__FILE__) . "/example.inc.php";
require dirname(__FILE__) . "/classes/cd.class.php";

$container = S2ContainerFactory::create("./resource/cd.dicon.xml");
$cd = $container->getComponent("cd");

var_dump($cd->find_all());
?>

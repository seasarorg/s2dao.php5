<?php
require_once dirname(__FILE__) . "/example.inc.php";
require dirname(__FILE__) . "/classes/cd.class.php";

$container = S2ContainerFactory::create("./resource/cd.dicon.xml");
$cd = $container->getComponent("cd");

$cd->setId(1);
$cd->setTitle("Harvest");
$cd->setContent("rock");
var_dump('insert: ' . $cd->insert(), $cd->toString());

$cd->setTitle("Bring It");
var_dump('update: ' . $cd->update(), $cd->toString());

var_dump('delete: ' . $cd->delete(), $cd->toString());

?>

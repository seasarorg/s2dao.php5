<?php
require_once dirname(__FILE__) . "/example.inc.php";
require dirname(__FILE__) . "/classes/cd.class.php";

$container = S2ContainerFactory::create("./resource/cd.dicon.xml");
$cd = $container->getComponent("Cd");

$cd->id = 2;
$cd->title = 'do you remember me ?';
$cd->content = 'j-pop';
var_dump('save as :' . $cd->save());
var_dump($cd->toString());

$cd->title = 'maria';
$cd->content = null;
var_dump('save as :' . $cd->save());
var_dump($cd->toString());

var_dump('delete as :' . $cd->delete());
var_dump($cd->toString());

?>

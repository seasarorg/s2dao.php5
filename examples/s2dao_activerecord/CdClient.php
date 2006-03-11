<?php
require_once dirname(__FILE__) . "/example.inc.php";
require dirname(__FILE__) . "/classes/cd.class.php";

$container = S2ContainerFactory::create("./resource/cd.dicon.xml");
$cd = $container->getComponent("Cd");

$cd->id = 1;
$cd->title = 'do you remember me ?';
$cd->content = 'j-pop';
var_dump('insert: ' . $cd->insert(), $cd->toString());

$cd->title = 'maria';
$cd->content = null;
var_dump('update: ' . $cd->update(), $cd->toString());

$cd->title = 'kid ...';
var_dump('save: ' . $cd->save(), $cd->toString());

var_dump('delete: ' . $cd->delete(), $cd->toString());

?>

<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon.xml");
$dao = $container->getComponent("ShelfDao");
$shelf = new ShelfBean();

$shelf->setId(1);
$shelf->setCdId(3);
$shelf->setTime("2005-12-24 12:34:56");
$dao->update($shelf);
?>

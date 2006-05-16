<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon.xml");
$dao = $container->getComponent("ShelfDao");
$shelf = new ShelfBean();

$shelf->setId(4);
$shelf->setCdId(1);
$dao->delete($shelf);
?>

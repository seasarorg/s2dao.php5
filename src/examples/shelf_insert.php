<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon.xml");
$dao = $container->getComponent("ShelfDao");
$shelf = new ShelfBean();

$shelf->setCdId(2);
$shelf->setAddTime("2005-12-18 10:11:09");
$dao->insert($shelf);

?>

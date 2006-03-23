<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon.xml");
$dao = $container->getComponent("ShelfDao");

var_dump($dao->SearchList(5, 2));
var_dump($dao->SearchByTimeList());
var_dump($dao->SearchByTrueList(date("Y-m-d H:i:s")));
?>

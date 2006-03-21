<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon.xml");
$dao = $container->getComponent("ShelfDao");

var_dump($dao->List_Search(5, 2));
var_dump($dao->List_SearchByTime());
var_dump($dao->List_SearchByTrue(date("Y-m-d H:i:s")));
?>

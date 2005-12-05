<?php

require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("example.dicon.xml");
$dao = $container->getComponent("daoImpl");

var_dump($dao->getCd(2));

?>
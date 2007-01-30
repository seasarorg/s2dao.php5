<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon");
$dao = $container->getComponent("CdDao");

var_dump($dao->getCdCount());

?>

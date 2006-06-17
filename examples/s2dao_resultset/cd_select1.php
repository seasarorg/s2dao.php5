<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon.xml");
$dao = $container->getComponent("CdDao");

var_dump($dao->getAll());
echo "=====" . PHP_EOL;
var_dump($dao->getSelectCdById(1));
?>

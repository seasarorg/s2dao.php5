<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon.xml");
$dao = $container->getComponent("CdDao");

var_dump($dao->getCD1(2, 'aaa'));
echo "=====" . PHP_EOL;
var_dump($dao->getCD2(2, 'help!', 'rock'));
?>

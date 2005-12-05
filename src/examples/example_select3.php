<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("example.dicon.xml");
$dao = $container->getComponent("beanCdDao");

$list = $dao->List_getCD3(null);
$cds = $list->toArray();
for($i = 0; $i < $list->size(); $i++){
    echo "ID: " . $cds[$i]->getId() . PHP_EOL;
    echo "TITLE: " . $cds[$i]->getTitle() . PHP_EOL;
    echo "CONTENT: " . $cds[$i]->getContent() . PHP_EOL;
    echo "-------" . PHP_EOL;
}

$hoge = $dao->getCds();
var_dump($hoge);
?>
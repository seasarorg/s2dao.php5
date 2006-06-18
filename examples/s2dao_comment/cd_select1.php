<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon.xml");
$dao = $container->getComponent("CdDao");

$cds = $dao->getAll();
foreach($cds as $cd){
    $cd = current($cd);
    echo "ID: " . $cd["id"] . PHP_EOL;
    echo "TITLE: " . $cd["title"] . PHP_EOL;
    echo "CONTENT: " . $cd["content"] . PHP_EOL;
    echo "-------" . PHP_EOL;
}

echo "=====" . PHP_EOL;

$cds = $dao->getSelectCdById(1);
foreach($cds as $cd){
    $cd = current($cd);
    echo "ID: " . $cd["id"] . PHP_EOL;
    echo "TITLE: " . $cd["title"] . PHP_EOL;
    echo "CONTENT: " . $cd["content"] . PHP_EOL;
    echo "-------" . PHP_EOL;
}

?>

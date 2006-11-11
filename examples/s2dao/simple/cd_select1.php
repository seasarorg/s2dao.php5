<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon");
$dao = $container->getComponent("CdDao");

// $cd is array()
$cds = $dao->getAllCDList();
for($i = 0; $i < $cds->size(); $i++){
    $cd = $cds->get($i);
    echo "ID: " . $cd->getId() . PHP_EOL;
    echo "TITLE: " . $cd->getTitle() . PHP_EOL;
    echo "CONTENT: " . $cd->getContent() . PHP_EOL;
    echo "-------" . PHP_EOL;
}

echo "=====" . PHP_EOL;

$cds = $dao->getSelectCdByIdArray(20);
foreach($cds as $cd){
    $cd = current($cd);
    echo "ID: " . $cd["id"] . PHP_EOL;
    echo "TITLE: " . $cd["title"] . PHP_EOL;
    echo "CONTENT: " . $cd["content"] . PHP_EOL;
    echo "-------" . PHP_EOL;
}

?>

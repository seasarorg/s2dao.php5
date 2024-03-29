<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon.xml");
$dao = $container->getComponent("CdDao");

$list = $dao->getCD3(null);
$cds = $list->toArray();
for($i = 0; $i < $list->size(); $i++){
    echo "ID: " . $cds[$i]->getId() . PHP_EOL;
    echo "TITLE: " . $cds[$i]->getTitle() . PHP_EOL;
    echo "CONTENT: " . $cds[$i]->getContent() . PHP_EOL;
    echo "-------" . PHP_EOL;
}

echo "======" . PHP_EOL;

$cds = $dao->getCds();
foreach($cds as $cd){
    $cd = current($cd);
    echo "ID: " . $cd["id"] . PHP_EOL;
    echo "TITLE: " . $cd["title"] . PHP_EOL;
    echo "CONTENT: " . $cd["content"] . PHP_EOL;
    echo "-------" . PHP_EOL;
}
?>

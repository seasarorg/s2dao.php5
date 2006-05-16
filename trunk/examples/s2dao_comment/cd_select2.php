<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon.xml");
$dao = $container->getComponent("CdDao");

$list = $dao->getCD1(2, 'aaa');
for($i = 0; $i < $list->size(); $i++){
    $cd = $list->get($i);
    echo "ID: " . $cd->getId() . PHP_EOL;
    echo "TITLE: " . $cd->getTitle() . PHP_EOL;
    echo "CONTENT: " . $cd->getContent() . PHP_EOL;
    echo "-------" . PHP_EOL;
}

echo "=====" . PHP_EOL;

$list = $dao->getCD2(2, 'help!', 'rock');
$i = $list->getIterator();
while($i->valid()){
    $cd = $i->current();
    echo "ID: " . $cd->getId() . PHP_EOL;
    echo "TITLE: " . $cd->getTitle() . PHP_EOL;
    echo "CONTENT: " . $cd->getContent() . PHP_EOL;
    echo "-------" . PHP_EOL;
    $i->next();
}

?>

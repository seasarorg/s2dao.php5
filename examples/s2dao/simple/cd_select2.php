<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon");
$dao = $container->getComponent("CdDao");

// $list instanceof S2Dao_ArrayList
$list = $dao->getCD1List(2, 'aaa');
for($i = 0; $i < $list->size(); $i++){
    // $cd instanceof CdBean
    $cd = $list->get($i);
    echo "ID: " . $cd->getId() . PHP_EOL;
    echo "TITLE: " . $cd->getTitle() . PHP_EOL;
    echo "CONTENT: " . $cd->getContent() . PHP_EOL;
    echo "-------" . PHP_EOL;
}

echo "=====" . PHP_EOL;

$list = $dao->getCD2List(2, 'help!', 'rock');
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

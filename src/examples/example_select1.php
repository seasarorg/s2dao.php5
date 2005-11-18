<?php
require_once dirname(__FILE__) . "/example.inc.php";
require dirname(__FILE__) . "/CdDao.class.php";
require dirname(__FILE__) . "/CdBean.class.php";

$container = S2ContainerFactory::create("example.dicon.xml");
$dao = $container->getComponent("CdDao");

// $bean instanceof ArrayObject
$bean = $dao->Array_getAllCD();
$iterator = $bean->getIterator();

for($iterator->rewind(); $iterator->valid(); $iterator->next()){
    // cd instanceof CdBean
    $cd = $iterator->current();
    echo "ID: " . $cd->getId() . PHP_EOL;
    echo "TITLE: " . $cd->getTitle() . PHP_EOL;
    echo "CONTENT: " . $cd->getContent() . PHP_EOL;
    echo "-------".PHP_EOL;
}

?>

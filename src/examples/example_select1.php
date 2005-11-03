<?php
require_once dirname(dirname(dirname(__FILE__))) . "/s2dao.inc.php";

require "CdDao.class.php";
require "CdBean.class.php";

$container = S2ContainerFactory::create("example.dicon");
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

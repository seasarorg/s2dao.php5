<?php
require_once dirname(dirname(dirname(__FILE__))) . "/s2dao.inc.php";

require "CdDao.class.php";
require "CdBean.class.php";

$container = S2ContainerFactory::create("example.dicon");
$dao = $container->getComponent("CdDao");

// $list instanceof ArrayList
$list = $dao->List_getCD1(2, 'aaa');
for($i = 0; $i < $list->size(); $i++){
    // $cd instanceof CdBean
    $cd = $list->get($i);
    echo "ID: " . $cd->getId() . PHP_EOL;
    echo "TITLE: " . $cd->getTitle() . PHP_EOL;
    echo "CONTENT: " . $cd->getContent() . PHP_EOL;
    echo "-------".PHP_EOL;
}

$list = $dao->List_getCD2(2, 'help!', 'rock');
for($i = 0; $i < $list->size(); $i++){
    $cd = $list->get($i);
    echo "ID: " . $cd->getId() . PHP_EOL;
    echo "TITLE: " . $cd->getTitle() . PHP_EOL;
    echo "CONTENT: " . $cd->getContent() . PHP_EOL;
    echo "-------".PHP_EOL;
}

?>

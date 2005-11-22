<?php
require_once dirname(__FILE__) . "/example.inc.php";
require dirname(__FILE__) . "/CdDao.class.php";
require dirname(__FILE__) . "/CdBean.class.php";

$container = S2ContainerFactory::create("example.dicon.xml");
$dao = $container->getComponent("CdDao");

// $cd is array()
$cd = $dao->Array_getAllCD();

for($i = 0; $i < count($cd); $i++){
    // $cd[$i] instanceof CdBean
    echo "ID: " . $cd[$i]->getId() . PHP_EOL;
    echo "TITLE: " . $cd[$i]->getTitle() . PHP_EOL;
    echo "CONTENT: " . $cd[$i]->getContent() . PHP_EOL;
    echo "-------" . PHP_EOL;
}

echo "=====" . PHP_EOL;

$cd = $dao->Array_getSelectCdById(20);
foreach($cd as $bean){
    echo "ID: " . $bean->getId() . PHP_EOL;
    echo "TITLE: " . $bean->getTitle() . PHP_EOL;
    echo "CONTENT: " . $bean->getContent() . PHP_EOL;
    echo "-------" . PHP_EOL;
}

?>

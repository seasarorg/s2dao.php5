<?php
require_once dirname(__FILE__) . "/orign.inc.php";
$container = S2ContainerFactory::create("./resource/StoredProcedureTestDao.dicon");
$container->init();

$dao = $container->getComponent("StoredProcedureTestDao");
//echo "SALES_TAX(1000) = " . $dao->getSalesTax(1000) . PHP_EOL;
echo "SALES_TAX2(1000) = " . $dao->getSalesTax2(1000) . PHP_EOL;
//echo "SALES_TAX3(1000) = " . $dao->getSalesTax3(1000) . PHP_EOL;
//echo "SALES_TAX4(1000) = " . $dao->getSalesTax4(1000) . PHP_EOL;
$container->destroy();

?>
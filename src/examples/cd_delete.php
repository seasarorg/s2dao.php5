<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("example.dicon.xml");
$dao = $container->getComponent("beanCdDao");
$cd = new CdBean();

$cd->setId(2);
$dao->delete($cd);
//$dao->remove($cd);

?>

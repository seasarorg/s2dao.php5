<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon");
$dao = $container->getComponent("CdDao");
$cd = new CdBean();

$cd->setId(2);
$dao->delete($cd);
//$dao->remove($cd);

?>

<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon.xml");
$dao = $container->getComponent("CdDao");
$cd = new CdBean();

$cd->setId(2);
$cd->setTitle("ride on technology");
$dao->update($cd);

?>

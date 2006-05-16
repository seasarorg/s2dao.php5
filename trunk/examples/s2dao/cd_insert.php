<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/example.dicon.xml");
$dao = $container->getComponent("beanCdDao");
$cd = new CdBean();

$cd->setId(2);
$cd->setTitle("gonna rice");
$cd->setContent("Techno");
$dao->insert($cd);
//$dao->create($cd);
//$dao->add($cd);

?>

<?php
require_once dirname(__FILE__) . "/example.inc.php";
require dirname(__FILE__) . "/CdDao.class.php";
require dirname(__FILE__) . "/CdBean.class.php";

$container = S2ContainerFactory::create("example.dicon.xml");
$dao = $container->getComponent("CdDao");
$cd = new CdBean();

$cd->setId(2);
$cd->setTitle("gonna rice");
$cd->setContent("Techno");
$dao->insert($cd);
//$dao->create($cd);
//$dao->add($cd);

?>

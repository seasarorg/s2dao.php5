<?php
require_once dirname(__FILE__) . "/example.inc.php";
require dirname(__FILE__) . "/CdDao.class.php";
require dirname(__FILE__) . "/CdBean.class.php";

$container = S2ContainerFactory::create("example.dicon.xml");
$dao = $container->getComponent("CdDao");
$cd = new CdBean();

$cd->setId(2);
$cd->setTitle("ride on technology");
$dao->update($cd);
//$dao->modify($cd);
//$dao->store($cd);

?>

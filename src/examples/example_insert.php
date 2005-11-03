<?php
require_once dirname(dirname(dirname(__FILE__))) . "/s2dao.inc.php";

require "CdDao.class.php";
require "CdBean.class.php";

$container = S2ContainerFactory::create("example.dicon");
$dao = $container->getComponent("CdDao");
$cd = new CdBean();

$cd->setId(2);
$cd->setTitle("gonna rice");
$cd->setContent("Techno");
$dao->insert($cd);
//$dao->create($cd);
//$dao->add($cd);

?>

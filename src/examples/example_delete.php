<?php
require_once dirname(dirname(dirname(__FILE__))) . "/s2dao.inc.php";

require "CdDao.class.php";
require "CdBean.class.php";

$container = S2ContainerFactory::create("example.dicon");
$dao = $container->getComponent("CdDao");
$cd = new CdBean();

$cd->setId(2);
$dao->delete($cd);
//$dao->remove($cd);

?>

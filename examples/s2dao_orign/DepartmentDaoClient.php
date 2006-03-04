<?php
require_once dirname(__FILE__) . "/orign.inc.php";

$container = S2ContainerFactory::create("./resource/DepartmentDao.dicon");
$dao = $container->getComponent("DepartmentDao");
$dept = new Department();

$dept->setDeptno(99);
$dept->setDname("foo");
$dao->insert($dept);
$dept->setDname("bar");

echo "before update versionNo:" . $dept->getVersionNo() . PHP_EOL;
$dao->update($dept);
echo "after update versionNo:" . $dept->getVersionNo() . PHP_EOL;
$dao->delete($dept);

$container->destroy();
?>

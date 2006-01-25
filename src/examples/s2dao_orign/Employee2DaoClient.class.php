<?php
require_once dirname(__FILE__) . "/orign.inc.php";

$container = S2ContainerFactory::create("./resource/Employee2Dao.dicon");
$dao = $container->getComponent("Employee2Dao");
$employees = $dao->getEmployees("CO");
for ($i = 0; $i < $employees->size(); ++$i) {
    var_dump($employees->get($i)->toString());
}
$employee = $dao->getEmployee(7788);
var_dump($employee->toString());

$container->destroy();
?>

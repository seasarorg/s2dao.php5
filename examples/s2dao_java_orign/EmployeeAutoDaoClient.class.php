<?php
require_once dirname(__FILE__) . "/orign.inc.php";

$container = S2ContainerFactory::create("./resource/EmployeeAutoDao.dicon");
$dao = $container->getComponent("EmployeeAutoDao");

$dao->getEmployeeByJobDeptnoList(null, null);
$dao->getEmployeeByJobDeptnoList("CLERK", null);
$dao->getEmployeeByJobDeptnoList(null, 20);
$dao->getEmployeeByJobDeptnoList("CLERK", 20);

$employees = $dao->getEmployeesBySalList(0, 1000);
for ($i = 0; $i < $employees->size(); ++$i) {
    var_dump($employees->get($i)->toString());
}

$employees = $dao->getEmployeeByDnameList("SALES");
for ($i = 0; $i < $employees->size(); ++$i) {
    var_dump($employees->get($i)->toString());
}

$dto = new EmployeeSearchCondition();
$dto->setDname("RESEARCH");
$employees = $dao->getEmployeesBySearchCondition($dto);
for ($i = 0; $i < $employees->size(); ++$i) {
    var_dump($employees->get($i)->toString());
}

$employee = $dao->getEmployeeByEmpno(7788);
var_dump("before timestamp:" . $employee->getTimestamp());
$dao->update($employee);
var_dump("after timestamp:" . $employee->getTimestamp());

$container->destroy();
?>

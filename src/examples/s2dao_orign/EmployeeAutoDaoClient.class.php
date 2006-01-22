<?php
require_once dirname(__FILE__) . "/orign.inc.php";

$container = S2ContainerFactory::create("./resource/EmployeeAutoDao.dicon");
$dao = $container->getComponent("EmployeeAutoDao");

$dao->List_getEmployeeByJobDeptno(null, null);
$dao->List_getEmployeeByJobDeptno("CLERK", null);
$dao->List_getEmployeeByJobDeptno(null, 20);
$dao->List_getEmployeeByJobDeptno("CLERK", 20);

$employees = $dao->List_getEmployeesBySal(0, 1000);
for ($i = 0; $i < $employees->size(); ++$i) {
    var_dump($employees->get($i));
}

$employees = $dao->List_getEmployeeByDname("SALES");
for ($i = 0; $i < $employees->size(); ++$i) {
    var_dump($employees->get($i));
}

$dto = new EmployeeSearchCondition();
$dto->setDname("RESEARCH");
$employees = $dao->getEmployeesBySearchCondition($dto);
for ($i = 0; $i < $employees->size(); ++$i) {
    var_dump($employees->get($i));
}

$employee = $dao->getEmployeeByEmpno(7788);
var_dump("before timestamp:" . $employee->getTimestamp());
$dao->update($employee);
var_dump("after timestamp:" . $employee->getTimestamp());

$container->destroy();
?>

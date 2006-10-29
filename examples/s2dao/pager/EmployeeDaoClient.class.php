<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/EmployeeDao.dicon");
$dao = $container->getComponent("EmployeeDao");
$employees = $dao->getAllEmployeesList();
for ($i = 0; $i < $employees->size(); ++$i) {
    var_dump($employees->get($i)->toString());
}

$employee = $dao->getEmployee(7788);
var_dump($employee->toString());

$count = $dao->getCount();
var_dump("count:" . $count);

$dao->getEmployeeByJobDeptnoList(null, null);
$dao->getEmployeeByJobDeptnoList("CLERK", null);
$dao->getEmployeeByJobDeptnoList(null, 20);
$dao->getEmployeeByJobDeptnoList("CLERK", 20);
$dao->getEmployeeByDeptnoList(20);
$dao->getEmployeeByDeptnoList(null);

var_dump("updatedRows:" . $dao->update($employee));

$container->destroy();
?>

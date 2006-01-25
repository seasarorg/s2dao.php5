<?php
require_once dirname(__FILE__) . "/orign.inc.php";

$container = S2ContainerFactory::create("./resource/EmployeeDao.dicon");
$dao = $container->getComponent("EmployeeDao");
$employees = $dao->List_getAllEmployees();
for ($i = 0; $i < $employees->size(); ++$i) {
    var_dump($employees->get($i)->toString());
}

$employee = $dao->getEmployee(7788);
var_dump($employee->toString());

$count = $dao->getCount();
var_dump("count:" . $count);

$dao->List_getEmployeeByJobDeptno(null, null);
$dao->List_getEmployeeByJobDeptno("CLERK", null);
$dao->List_getEmployeeByJobDeptno(null, 20);
$dao->List_getEmployeeByJobDeptno("CLERK", 20);
$dao->List_getEmployeeByDeptno(20);
$dao->List_getEmployeeByDeptno(null);

var_dump("updatedRows:" . $dao->update($employee));

$container->destroy();
?>

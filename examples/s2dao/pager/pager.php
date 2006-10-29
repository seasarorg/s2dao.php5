<?php
require_once dirname(__FILE__) . "/example.inc.php";

$container = S2ContainerFactory::create("./resource/EmployeeAutoDao.dicon");
$dao = $container->getComponent("EmployeeAutoDao");

/** !-------------- S2Pager --------------------------------- **/
$dto = new EmployeeSearchCondition();
$dto->setDname("RESEARCH");
$dto->setLimit(3);
$employees = $dao->getEmployeesBySearchConditionList($dto);
for ($i = 0; $i < $employees->size(); ++$i) {
    var_dump($employees->get($i)->toString());
}

echo 'count:' . $dto->getCount() . "\n";
/** -------------- /S2Pager --------------------------------- **/

?>

<?php
$container = S2ContainerFactory::create(RESOURCE_DIR . "/pdo.dicon");
$ds = $container->getComponent("pdo.dataSource");
$pdo = $ds->getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

$pdo->beginTransaction();
$dbms = strtolower($driver);
$pdo->exec(file_get_contents(RESOURCE_DIR . "/test-" . $dbms . ".sql"));
$pdo->exec(file_get_contents(RESOURCE_DIR . "/test-procedure-" . $dbms . ".sql"));
$pdo->commit();
?>

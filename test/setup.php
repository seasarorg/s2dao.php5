<?php

/**
 * PDO::exec problem two or more lines SQL are not recognized...orz
 */
function sql_liner(PDO $pdo, $filePath){
    if(!file_exists($filePath)){
        return null;
    }
    $contents = file($filePath);
    foreach($contents as $sql){
        if(preg_match('/^(\r?\n|\s)+$/s', $sql) || $sql == PHP_EOL){
            continue;
        }
        $pdo->exec($sql);
    }
}

$container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
$ds = $container->getComponent("pdo.dataSource");

try {
    $pdo = $ds->getConnection();
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    
    $pdo->beginTransaction();
    $dbms = strtolower($driver);
    
    sql_liner($pdo, RESOURCE_DIR . "/test-" . $dbms . ".sql");
    // FIXME self-execute test-procedure-{dbms}.sql
    //sql_liner($pdo, RESOURCE_DIR . "/test-procedure-" . $dbms . ".sql");
    
    $pdo->commit();
} catch (Exception $e){
    var_dump($e);
    $e->rollback();
    die("setup failure" . PHP_EOL);
}

$pdo = null;
?>

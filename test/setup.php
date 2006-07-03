<?php

/**
 * PDO::exec problem two or more lines SQL are not recognized...orz
 */
function query_liner(PDO $pdo, array $contents){
    foreach($contents as $sql){
        if(preg_match('/^(\r?\n|\s)+$/s', $sql) || $sql == PHP_EOL){
            continue;
        }
        $pdo->exec($sql);
    }
}

function sql_liner(PDO $pdo, $filePath){
    if(!file_exists($filePath)){
        return null;
    }
    query_liner($pdo, file($filePath));
}

$container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
$ds = $container->getComponent("pdo.dataSource");

try {
    $pdo = $ds->getConnection();
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    
    $pdo->beginTransaction();
    $dbms = strtolower($driver);
    
    sql_liner($pdo, RESOURCE_DIR . "/test-" . $dbms . ".sql");
    // FIXME
    //sql_liner($pdo, RESOURCE_DIR . "/test-procedure-" . $dbms . ".sql");
    
    $pdo->commit();
} catch (Exception $e){
    var_dump($e);
    $e->rollback();
    die("setup failure" . PHP_EOL);
}

$pdo = null;
?>

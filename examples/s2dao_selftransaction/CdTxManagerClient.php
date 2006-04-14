<?php
require dirname(__FILE__) . "/example.inc.php";
require dirname(__FILE__) . "/CdTxManager.class.php";

$container = S2ContainerFactory::create("./resource/example.dicon.xml");
$manager = $container->getComponent("CdTxManager");

echo "requiredInsert start" . PHP_EOL;
try{
    $manager->requiredInsert();
}catch(Exception $e){
    var_dump($e->getMessage());
}
echo "requiredInsert end" . PHP_EOL;

echo "requiresNewInsert start" . PHP_EOL;
try{
    $manager->requiresNewInsert();
}catch(Exception $e){
    var_dump($e->getMessage());
}
echo "requiresNewInsert end" . PHP_EOL;

echo "mandatoryInsert start" . PHP_EOL;
try{
    $manager->mandatoryInsert();
}catch(Exception $e){
    var_dump($e->getMessage());
}
echo "mandatoryInsert end" . PHP_EOL;

echo "getAll start" . PHP_EOL;
try{
    var_dump($manager->getAll());
}catch(Exception $e){
    var_dump($e->getMessage());
}
echo "getAll end" . PHP_EOL;

?>
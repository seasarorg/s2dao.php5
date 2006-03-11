<?php

//class cd {}
//class shelf {}
class cd_shelf {}

require_once dirname(__FILE__) . "/example.inc.php";
$container = S2ContainerFactory::create("./resource/cd_shelf.dicon.xml");
$cdsh = $container->getComponent("cd_shelf");

?>
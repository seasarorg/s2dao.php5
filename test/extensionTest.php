<?php
require_once dirname(__FILE__) . "/test.condition.php";
//require_once dirname(__FILE__) . "/setup.php";

S2ContainerClassLoader::import(PACKAGE_DIR . "/extension/dataset");
S2ContainerClassLoader::import(PACKAGE_DIR . "/extension/db");
S2ContainerClassLoader::import(PACKAGE_DIR . "/extension/tx");

?>
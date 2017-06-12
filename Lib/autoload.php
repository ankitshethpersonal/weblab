<?php
/* this file not required now, as now autoload done with composer */
require_once("config.php");

function __autoload($className = "") {

    if ($className != "" && file_exists(BASE_PATH."/includes/".$className.".php")) {
        require_once(BASE_PATH."/includes/".$className.".php");
    } else if ($className != "" && file_exists(BASE_PATH."/resources/".$className.".php")) {
        require_once(BASE_PATH."/resources/".$className.".php");
    } 
}

//spl_autoload_register('resourceAutoload');

?>

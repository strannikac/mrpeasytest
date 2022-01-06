<?php

function projectAutoload(String $className) {
    $filename = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    if(file_exists($filename)) {
        require_once $filename;
    }
}

spl_autoload_register('projectAutoload');

?>
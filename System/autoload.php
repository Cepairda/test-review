<?php

function autoload($className)
{

    $className = ltrim($className, '\\');

    //echo $className;

    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        //echo $lastNsPos;
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . 
        DIRECTORY_SEPARATOR;
    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    //echo $fileName . '<br>';

    require_once $fileName;
}
spl_autoload_register('autoload');
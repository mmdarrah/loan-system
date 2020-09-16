<?php
// Autoload all the classes inside the classes folder
spl_autoload_register('autoLoader');

function autoLoader($className)
{

    $path = "classes/"; // All the classes should be in the classes folder
    $extension = ".class.php"; // All the classes should have the same extension
    $fullPath = $path . $className . $extension;

    // if the file name is not existed
    if (!file_exists($fullPath)) {
        return false;
    }

    include_once $fullPath;
}

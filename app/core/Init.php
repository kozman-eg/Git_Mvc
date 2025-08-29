<?php

defined('ROOTPATH') or exit('access allwoed');

spl_autoload_register(function ($class) {

    $class = ltrim($class, '\\');
    $classPath = [
        'core' => '',
        'models' => '',
        'controllers' => '',
        'else' => '',
    ];

    if (strpos($class, 'Core\\') === 0) {
        $class = substr($class, strlen('Core\\'));
        $classPath['core'] = str_replace('\\', '/', $class);
    }
    if (strpos($class, 'Models\\') === 0) {
        $class = substr($class, strlen('Models\\'));
        $classPath['models'] = str_replace('\\', '/', $class);
    }
    if (strpos($class, 'Controllers\\') === 0) {
        $class = substr($class, strlen('Controllers\\'));
        $classPath['controllers'] = str_replace('\\', '/', $class);
    }

    $classPath['else'] = str_replace('\\', '/', $class);


    $paths = [
        __DIR__ . '/../core/' . $classPath['core'] . '.php',
        __DIR__ . '/../models/' . $classPath['models'] . '.php',
        __DIR__ . '/../controllers/' . $classPath['controllers'] . '.php',
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    // لو الملف مش موجود
    if (!$found) {
        echo "❌ File not found for class: <b>$class</b><br>";
        echo "Tried paths:<br>";
        foreach ($paths as $p) {
            echo $p . "<br>";
        }
    }
});




require "Config.php";
require "Controller.php";
require "Functions.php";
require "Database.php";
require "Model.php";
require "App.php";
require __DIR__ . "/../models/Session.php";
require __DIR__ . "/../models/Request.php";

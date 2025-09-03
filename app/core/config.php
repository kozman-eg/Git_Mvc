<?php


defined('ROOTPATH') or exit('access allwoed');

// Config file
if ((empty($_SERVER['SERVER_NAME']) && php_sapi_name() !== 'cli') || (!empty($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost')) {

    define('DEV_MODE', true);
    define('DBNAME', 'shop');
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBDRIVER', '');
    define('ROOT', 'http://localhost/MVC/Git_Mvc/public');
    define('LANGS', 'http://localhost/MVC/Git_Mvc/languages/english.php');
} else {
    define('DEV_MODE', false);
    define('DBNAME', 'shop');
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBDRIVER', '');
    define('ROOT', 'https://github.com/kozman-eg/Git_Mvc.git');
}
define('APP_NAME', 'My Wepiste');
define('APP_DESC', 'Bast Wepiste on the planet');
define('DEBUG', true);

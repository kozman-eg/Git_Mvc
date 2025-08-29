<?php
session_start();
//echo phpversion();
$minPHPVersion = '8.2.12';
if (phpversion() < $minPHPVersion) {

    die("Yout php version must be {$minPHPVersion} or higher to run this app . Your current version " . phpversion());
}
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);

require '../app/core/Init.php';

DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

$app = new \Core\App;
$app->loadController();

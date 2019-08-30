<?php include("functions.php");
include(dirname(__FILE__) ."../../autoload.php");
$user = new \Invoice\Express\User();
$user->config->database->host = 'localhost';
$user->config->database->name =  'billing';
$user->config->database->user = 'admin';
$user->config->database->password = '1234';
$user->start();
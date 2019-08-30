<?php
/**
 * Express Invoice & Stock Manager
 * Copyright (c) Rajesh Dukiya. All Rights Reserved
 * ***********************************************************************
 *
 *  Email: support@skyresoft.com
 *  Website: https://www.skyresoft.com
 *
 *  ************************************************************************
 *  * This software is furnished under a license and may be used and copied
 *  * only  in  accordance  with  the  terms  of such  license and with the
 *  * inclusion of the above copyright notice.
 *  * If you Purchased from Codecanyon, Please read the full License from
 *  * here- http://skyresoft.com/licenses/standard/
 * ***********************************************************************
 */
include('functions.php');
include(dirname(__file__) . '../../autoload.php');
$user = new \Invoice\Express\User();

//Changes start from here
$user->config->database->host = "localhost"; //your database hostname
$user->config->database->name = "expressinvoice"; //your database name
$user->config->database->user = "root"; //your database username
$user->config->database->password = "1234"; //your database password
//Stop changes

$user->start();

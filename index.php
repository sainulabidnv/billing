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

/*
error reporting 
to set production environment please use 
error_reporting(0);
*/
error_reporting(E_ALL);

//installation checking
if (file_exists("includes/config.php")) {
    require_once("includes/config.php");
} else {
    header("location: install/index.php");
    exit;
}
// include pdo class wrapper
include_once 'lib/pdowrapper/class.pdowrapper.php';
// database connection setings
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$db = new PdoWrapper($dbConfig);
//site configration loading and login check
include('includes/globalcf.php');
include('includes/system.php');

//timezone settings
date_default_timezone_set($siteConfig['zone']);

//get page and content
if (isset($_GET['rdp'])) {
    $page = $_GET['rdp'];

    if (file_exists("system/$page.php")) {
        if ($user->isSigned()) {
            include('includes/header.php');
            include("system/$page.php");

        } else {
            redirect(SITE);
        }
    } else {
        //page not exist
        die("<div style='text-align: center;'><h3>Page doesn't exist !!</h3></div>");
    }
} else {
    include('includes/header-login.php');
    include("system/index.php");
}
//footer
include("includes/footer.php");

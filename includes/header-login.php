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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "header-login.php")) {
    die("Internal Server Error!");
}

?><!doctype html>
<html lang="en">
<head>
    <!--login header-->
    <meta charset="UTF-8">
    <title><?php

        echo $siteConfig['name'];

        ?></title>
    <base href="<?php

    echo SITE

    ?>/"/>
    <link href="lib/css/bootstrap.min.css" rel="stylesheet">
    <link href="lib/css/datepicker3.css" rel="stylesheet">
    <link href="lib/css/styles.css" rel="stylesheet">

    <link href="lib/css/icon.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

    <script src="lib/js/jQuery-2.2.0.min.js"></script>
    <script src="lib/js/bootstrap.min.js"></script>
    <script src="lib/js/moment.js"></script>
    <script src="lib/js/chart.min.js"></script>
    <script src="lib/js/Chart.Line.js"></script>
    <script src="lib/js/bootstrap.datetime.js"></script>
    <script src="lib/js/basic.js"></script>
</head>
<body style="background-color:#FBFBFB">
<div class="container">
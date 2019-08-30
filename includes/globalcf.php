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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "globalcf.php")) {
    die("Internal Server Error!");
}
//site configration
$query = "SELECT * FROM panel";
$row_conf = $db->select('panel')->results();
$siteConfig['name'] = $row_conf['cname'];
$siteConfig['address'] = $row_conf['address'];
$siteConfig['address2'] = $row_conf['address2'];
$siteConfig['phone'] = $row_conf['phone'];
$siteConfig['email'] = $row_conf['email'];
$siteConfig['vatno'] = $row_conf['cvatno'];
$siteConfig['vatrate'] = $row_conf['vatr'];
$siteConfig['vatrate2'] = $row_conf['vatr2'];
$siteConfig['vatst'] = $row_conf['vatst'];
$siteConfig['vinc'] = $row_conf['vinc'];
$siteConfig['curr'] = $row_conf['crncy'];
$siteConfig['fcurr'] = $row_conf['fcrncy'];
$siteConfig['pref'] = $row_conf['pref'];
$siteConfig['timezone'] = 5;

switch ($row_conf['dfomat']) {
    case 1:
        $siteConfig['date'] = date("d-m-Y");
        $siteConfig['dformat'] = "d-m-Y";
        $siteConfig['dformat2'] = "DD-MM-YYYY";
        break;
    case 2:
        $siteConfig['date'] = date("Y-m-d");
        $siteConfig['dformat'] = "Y-m-d";
        $siteConfig['dformat2'] = "YYYY-MM-DD";
        break;
    case 3:
        $siteConfig['date'] = date("m-d-Y");
        $siteConfig['dformat'] = "m-d-Y";
        $siteConfig['dformat2'] = "MM-DD-YYYY";
        break;
}
$siteConfig['zone'] = $row_conf['zone'];

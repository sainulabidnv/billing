<?php
/**
 * Express Invoice & Stock Manager
 * Copyright (c) Rajesh Dukiya. All Rights Reserved
 * ***********************************************************************
 *
 *  E-mail: support@expressinvoice.xyz
 *  Website: https://www.expressinvoice.xyz
 *
 *  ************************************************************************
 *  * This software is furnished under a license and may be used and copied
 *  * only  in  accordance  with  the  terms  of such  license and with the
 *  * inclusion of the above copyright notice.
 *  * If you Purchased from Codecanyon, Please read the full License from
 *  * here- http://skyresoft.com/licenses/standard/
 * ***********************************************************************
 */
include_once('../includes/config.php');
include_once('../includes/system.php');
include_once('../lib/pdowrapper/class.pdowrapper.php');
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$db = new PdoWrapper($dbConfig);
//timezone settings
include('../includes/globalcf.php');
date_default_timezone_set($siteConfig['zone']);
global $siteConfig, $user;
if (!$user->group_id <= 2) {
    if (isset($_POST['act'])) {
        $act = $_POST['act'];
    } else {
        die();
    }
    if ($act == 'superupdate') {
        $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        $mysqli->query("DELETE FROM summary ORDER BY id DESC limit 12");
        for ($i = 12; $i >= 2; $i--) {

            $month = date('m', strtotime("-$i month"));
            $year = date('Y', strtotime("-$i month"));
            $monthName = date("F", mktime(0, 0, 0, $month, 10));


            $isum = $db->sum('invoices', 'total', "(DATE(tsn_date) BETWEEN '$year-$month-01' AND '$year-$month-31') AND status='paid'");

            $dsum = $db->sum('invoices', 'total', "(DATE(tsn_date) BETWEEN '$year-$month-01' AND '$year-$month-31') AND status='due'");
            sleep(1);
            $dcoun = 0;
            $dcoun = $db->count('invoices', "(DATE(tsn_date) BETWEEN '$year-$month-01' AND '$year-$month-31') AND status='due'");
            $query = "INSERT INTO summary (id,mnth,yer,paid,due,unpaid) VALUES (null,'$monthName','$year','$isum','$dsum','$dcoun')";
            $db->pdoQuery($query)->results();
        }
        $wmonth = 2678400;
        $month = date('m', time() - $wmonth);
        $year = date('Y', time() - $wmonth);
        $monthName = date("F", mktime(0, 0, 0, $month, 10));
        $isum = $db->sum('invoices', 'total', "(DATE(tsn_date) BETWEEN '$year-$month-01' AND '$year-$month-31') AND status='paid'");

        $dsum = $db->sum('invoices', 'total', "(DATE(tsn_date) BETWEEN '$year-$month-01' AND '$year-$month-31') AND status='due'");
        sleep(1);
        $dcoun = 0;
        $dcoun = $db->count('invoices', "(DATE(tsn_date) BETWEEN '$year-$month-01' AND '$year-$month-31') AND status='due'");
        $query = "INSERT INTO summary (id,mnth,yer,paid,due,unpaid) VALUES (null,'$monthName','$year','$isum','$dsum','$dcoun')";
        $db->pdoQuery($query)->results();
    }

    if ($act == 'recsuperupdate') {
        $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        $mysqli->query("DELETE FROM rec_summary ORDER BY id DESC limit 12");
        for ($i = 12; $i >= 2; $i--) {

            $month = date('m', strtotime("-$i month"));
            $year = date('Y', strtotime("-$i month"));
            $monthName = date("F", mktime(0, 0, 0, $month, 10));


            $isum = $db->sum('rec_part_trans', 'amount', "(DATE(tdate) BETWEEN '$year-$month-01' AND '$year-$month-31') ");

            $dataArray = array('mnth' => $monthName, 'yer' => $year, 'paid' => intval($isum));

            $bill_csd = $db->insert('rec_summary', $dataArray)->getLastInsertId();
        }
        $wmonth = 2678400;
        $month = date('m', time() - $wmonth);
        $year = date('Y', time() - $wmonth);
        $monthName = date("F", mktime(0, 0, 0, $month, 10));
        $isum = $db->sum('rec_part_trans', 'amount', "(DATE(tdate) BETWEEN '$year-$month-01' AND '$year-$month-31') ");
        $isum = $db->sum('rec_part_trans', 'amount', "(DATE(tdate) BETWEEN '$year-$month-01' AND '$year-$month-31') ");

        $dataArray = array('mnth' => $monthName, 'yer' => $year, 'paid' => intval($isum));

        $bill_csd = $db->insert('rec_summary', $dataArray)->getLastInsertId();

    }

}
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
include('../includes/globalcf.php');
global $siteConfig, $user;
date_default_timezone_set($siteConfig['zone']);
if ($user->group_id <= 2) {


    $act = isset($_POST['act']) ? $_POST['act'] : "";


    if ($act == 'add_product') {
        $profit = 0;
        $fdate = date('Y-m-d', strtotime($_POST['fdate']));
        $tdate = date('Y-m-d', strtotime($_POST['tdate']));
        $query = "SELECT poutcum FROM paic WHERE (DATE(pdate) BETWEEN '$fdate' AND '$tdate')";
        $result = $db->pdoQuery($query)->results();
        foreach ($result as $row) {
            $profit += $row['poutcum'];
        }


        header('Content-Type: application/json');


        if ($result) {

            echo json_encode(array('status' => 'Success', 'message' =>
                "Total Profit between $fdate to $tdate is $profit"));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'It seems there is no invoice.'));
        }


    }
    if ($act == 'iadd_product') {

        $profit = 0;
        $fdate = intval($_POST['ist']);
        $tdate = $_POST['iend'];
        if ($fdate < $tdate) {
            $query = "SELECT poutcum FROM paic WHERE tid BETWEEN '$fdate' AND '$tdate'";
            $result = $db->pdoQuery($query)->results();
            foreach ($result as $row) {
                $profit += $row['poutcum'];
            }
        } else {
            $result = false;
        }


        header('Content-Type: application/json');


        if ($result) {

            echo json_encode(array('status' => 'Success', 'message' =>
                "Total Profit from invoice $fdate to $tdate is $profit"));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, Starting invoice no should be smaller than end/Invalid invoice number.'));
        }


    }


} else {

    echo json_encode(array('status' => 'Error', 'message' =>
        'You are not authorized for this action. Action reverted!!'));
}

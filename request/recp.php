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
if (isset($_POST['act'])) {
    $action = $_POST['act'];
} else {
    die();
}
include_once('../includes/config.php');
include_once('../lib/pdowrapper/class.pdowrapper.php');
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$db = new PdoWrapper($dbConfig);

include('../includes/globalcf.php');
global $user, $siteConfig;
date_default_timezone_set($siteConfig['zone']);

include_once('../includes/system.php');
if (!$user->isSigned()) {
    die('log in');
}
if ($action == 'rec_payment') {

    $id = intval($_POST["send"]);

    if ($id > 0) {


        $bill_date = $_POST["rcnext"];
        $colm = array('total', 'rperiod');
        $whereConditions = array('tid' => $id);
        $result1 = $db->select('rec_invoices', $colm, $whereConditions)->results();
        $total = $result1['total'];
        $rperiod = $result1['rperiod'];
        switch ($rperiod) {
            case 1:
                $rc_next = date("Y-m-d", strtotime("+7 days", strtotime($bill_date)));
                break;
            case 2:
                $rc_next = date("Y-m-d", strtotime("+15 days", strtotime($bill_date)));
                break;
            case 3:
                $rc_next = date("Y-m-d", strtotime("+30 days", strtotime($bill_date)));
                break;
            case 4:
                $rc_next = date("Y-m-d", strtotime("+3 months", strtotime($bill_date)));
                break;
            case 5:
                $rc_next = date("Y-m-d", strtotime("+6 months", strtotime($bill_date)));
                break;
            case 6:
                $rc_next = date("Y-m-d", strtotime("+1 years", strtotime($bill_date)));
                break;
            case 7:
                $rc_next = date("Y-m-d", strtotime("+3 years", strtotime($bill_date)));
                break;
        }

        $tod = date("Y-m-d");


        $query = "UPDATE rec_invoices SET rc_next = '$rc_next', rc_up = '$tod',ramm = ramm + $total WHERE tid='$id'";
        $aBindWhereParam = array('tid' => $id);
        $db->pdoQuery($query, $aBindWhereParam)->results();

        $dataArray = array('tid' => $id, 'amount' => $total, 'tdate' => $tod);
        $status = $db->insert('rec_part_trans', $dataArray)->rStatus();

        //   if ($status == true) {
        echo json_encode(array('status' => 'Success', 'message' =>
            'Payment has been added successfully!'));
        //    } else {
        //     echo json_encode(array('status' => 'Error', 'message' =>
        //          'There has been an error, please try again.'));
        //    }
    }

}
if ($action == 'can_payment') {

    $id = intval($_POST["send"]);

    if ($id > 0) {


        $dataArray = array('active' => 1);
        $aWhere = array('tid' => $id);
        $status = $db->update('rec_invoices', $dataArray, $aWhere)->rStatus();


        echo json_encode(array('status' => 'Success', 'message' =>
            'Invoice has been canceled successfully!'));


    }

}
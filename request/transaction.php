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


    if ($act == 'add_transaction') {

        $tsno = $_POST['tsno'];
        $tamount = $_POST['tamount'];
        $ac_no = $_POST['acno'];
        $stat = $_POST['stat'];
        $tdate = $_POST['tdate'];
        $tnote = $_POST['tnote'];
        $ttime = date("H:i:s");
        $mysqlDate = date('Y-m-d', strtotime($tdate)) . ' ' . $ttime;

        $dataArray = array('tno' => $tsno, 'acid' => $ac_no, 'amount' => $tamount, 'stat' => $stat, 'tdate' => $mysqlDate, 'note' => $tnote);
        header('Content-Type: application/json');
        $status = $db->insert('ac_transactions', $dataArray)->rStatus();


        if ($status == true) {

            if ($stat == 'Cr') {
                $query = "UPDATE bank_ac SET lastbal = lastbal + $tamount WHERE id='$ac_no'";
            } else {
                $query = "UPDATE bank_ac SET lastbal = lastbal - $tamount WHERE id='$ac_no'";
            }
            $aBindWhereParam = array('id' => $ac_no);
            $db->pdoQuery($query, $aBindWhereParam)->results();

            echo json_encode(array('status' => 'Success', 'message' =>
                "Transaction has been added successfully! "));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again. Transaction id must be unique.'));
        }


    }

    if ($act == 'delete_transaction') {


        $id = $_POST["delete"];

        $aWhere = array('id' => $id);
        $tdss = $db->select('ac_transactions', array('acid', 'amount', 'stat'), $aWhere)->results();
        $acid = $tdss['acid'];
        $dam = $tdss['amount'];
        $stat = $tdss['stat'];


        if ($stat == 'Cr') {
            $query = "UPDATE bank_ac SET lastbal = lastbal - $dam WHERE id='$acid'";
        } else {
            $query = "UPDATE bank_ac SET lastbal = lastbal + $dam WHERE id='$acid'";
        }
        $aBindWhereParam = array('id' => $acid);
        $db->pdoQuery($query, $aBindWhereParam)->results();


        $status = $db->delete('ac_transactions', $aWhere, 'LIMIT 1')->affectedRows();

        if ($status == 1) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Transaction has been deleted successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }
} else {

    echo json_encode(array('status' => 'Error', 'message' =>
        'You are not authorized for this action. Action reverted!!'));
}

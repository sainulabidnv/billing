<?php
/**
 * Express Invoice & Stock Manager
 * Copyright (c) Rajesh Dukiya. All Rights Reserved
 * ***********************************************************************
 *
 *  E-mail: support@skyresoft.com
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

include_once('../includes/config.php');
include_once('../includes/system.php');
include_once('../lib/pdowrapper/class.pdowrapper.php');
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$db = new PdoWrapper($dbConfig);

global $siteConfig, $user;
if ($user->group_id <= 2) {

    $act = isset($_POST['act']) ? $_POST['act'] : "";


    if ($act == 'add_account') {

        $holder_name = $_POST['holder_name'];
        $bank_name = $_POST['bank_name'];
        $ac_no = $_POST['ac_no'];
        $obal = $_POST['o_bal'];


        $dataArray = array('acn' => $ac_no, 'holder' => $holder_name, 'bank' => $bank_name, 'lastbal' => $obal);

        header('Content-Type: application/json');

        $status = $db->insert('bank_ac', $dataArray)->rStatus();
        if ($status == true) {

            echo json_encode(array('status' => 'Success', 'message' =>
                "Account has been added successfully!"));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                "There has been an error, please try again. Account number must be unique."));
        }


    }

    if ($act == 'update_account') {


        $getID = $_POST['id'];
        $holder_name = $_POST['holder_name'];
        $bank_name = $_POST['bank_name'];
        $ac_no = $_POST['ac_no'];


        $dataArray = array('acn' => $ac_no, 'holder' => $holder_name, 'bank' => $bank_name);
        $aWhere = array('id' => $getID);
        $data = $db->update('bank_ac', $dataArray, $aWhere);

        if ($data) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Account has been updated successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }
    if ($act == 'link_account') {


        $getID = $_POST['acid'];


        $dataArray = array('bank' => 1, 'acid' => $getID);
        $aWhere = array('id' => 1);
        $data = $db->update('conf', $dataArray, $aWhere);

        if ($data) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Account has been linked successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }
    if ($act == 'setgoal') {

        $getID = 1;
        $holder_name = intval($_POST['holder_name']);
        $bank_name = intval($_POST['bank_name']);
        $sbank_name = intval($_POST['sbank_name']);
        $rbank_name = intval($_POST['rbank_name']);
        $rsbank_name = intval($_POST['rsbank_name']);
        $ac_no = intval($_POST['ac_no']);

        $query = "UPDATE goals SET
				income = ?,
				expense = ?,
				sales = ?,
				invoices = ?,
				rsales = ?,
				rinvoices = ?
			 WHERE id = ?
			";

        $dataArray = array('income' => $ac_no, 'expense' => $holder_name, 'sales' => $sbank_name, 'invoices' => $bank_name, 'rsales' => $rsbank_name, 'rinvoices' => $rbank_name);
        $aWhere = array('id' => $getID);
        $data = $db->update('goals', $dataArray, $aWhere);

        if ($data) {
            echo json_encode(array('status' => 'Success', 'message' =>
                'Goal has been updated successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }

    if ($act == 'delete_account') {


        $id = $_POST["delete"];


        $aWhere = array('id' => $id);
        $tWhere = array('acid' => $id);
        $limit = 'LIMIT 1';
        $status = $db->delete('bank_ac', $aWhere, $limit)->affectedRows();
        $db->delete('ac_transactions', $tWhere)->affectedRows();

        if ($status == 1) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Account has been deleted successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }
    if ($act == 'add_balance') {

        $bdate = $_POST['bdate'];
        $bamount = $_POST['bamount'];
        $stat = $_POST['stat'];
        if($_POST['bnote'] =='') $bnote = 'Note'; else $bnote = $_POST['bnote'];
        $bcutomer = $_POST['grahak_id'];
        $bcategory = $_POST['category'];
        
        $mysqlDate = date('Y-m-d', strtotime($bdate));

        $dataArray = array('bdate' => $mysqlDate, 'amount' => $bamount, 'stat' => $stat, 'note' => $bnote, 'uid' => $bcutomer, 'category' => $bcategory );
        header('Content-Type: application/json');
        $status = $db->insert('ac_balance', $dataArray)->rStatus();

        if ($status == true) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Transaction has been added successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }
    if ($act == 'delete_btran') {


        $id = $_POST["delete"];


        $aWhere = array('id' => $id);


        if ($db->delete('ac_balance', $aWhere)) {

            echo json_encode(array('status' => 'Success', 'message' =>
                ' Transaction has been deleted successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }

} else {

    echo json_encode(array('status' => 'Error', 'message' =>
        'You are not authorized for this action. Action reverted!!'));
}

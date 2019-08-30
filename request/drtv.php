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
include_once('../includes/globalcf.php');
global $siteConfig, $user;
if (!$user->isSigned()) {
    die('log in');
}
$eid = $user->id;
$act = isset($_POST['act']) ? $_POST['act'] : "";

switch ($act) {
    case "create_quote" :
        $crepeat = '';
        $bill_tax = 0;
        $bill_number = intval($_POST['invoice_id']);
        $bill_date = date('Y-m-d', strtotime($_POST['tsn_date']));
        $bill_due_date = date('Y-m-d', strtotime($_POST['tsn_due']));
        $bill_upyog = $_POST['bill_upyog'];
        $bill_shipping = $_POST['saman_deliv'];
        $bill_disc = $_POST['bill_fdisc'];
        $bill_discr = $_POST['invoice_vvt'];
        if (isset($_POST['bill_ptax'])) {
            $bill_tax = $_POST['bill_ptax'];
        }
        $bill_yog = $_POST['bill_yog'];
        $bill_notes = $_POST['invoice_notes'];
        $bill_status = $_POST['invoice_status'];
        $bill_csd = $_POST['grahak_id'];
        if (isset($_POST['crepeat'])) {
            $crepeat = $_POST['crepeat'];
        }
        $query = "";

        if ($crepeat == "yes" and $bill_csd == "0") {
            $grahak_name = $_POST['grahak_name'];
            $grahak_adrs1 = $_POST['grahak_adrs1'];
            $grahak_adrs2 = $_POST['grahak_adrs2'];
            $grahak_phone = $_POST['grahak_phone'];
            $grahak_email = $_POST['grahak_email'];
            $grahak_tax = $_POST['grahak_tax'];


            $dataArray = array('name' => $grahak_name, 'address1' => $grahak_adrs1, 'address2' => $grahak_adrs2, 'phone' => $grahak_phone, 'email' => $grahak_email, 'taxid' => $grahak_tax, 'rdate' => $bill_date);

            $bill_csd = $db->insert('reg_customers', $dataArray)->getLastInsertId();


        } else
            if ($bill_csd == "0") {
                $grahak_name = $_POST['grahak_name'];
                $grahak_adrs1 = $_POST['grahak_adrs1'];
                $grahak_adrs2 = $_POST['grahak_adrs2'];
                $grahak_phone = $_POST['grahak_phone'];
                $grahak_email = $_POST['grahak_email'];
                $grahak_tax = $_POST['grahak_tax'];

                $dataArray = array('tid' => $bill_number, 'name' => $grahak_name, 'address1' => $grahak_adrs1, 'address2' => $grahak_adrs2, 'phone' => $grahak_phone, 'email' => $grahak_email, 'taxid' => $grahak_tax);
                $dbg = $db->insert('return_customers', $dataArray);
            }

        if (!is_numeric($bill_yog)) {
            die();
        }
        $dataArray = array('tid' => $bill_number, 'tsn_date' => $bill_date, 'tsn_due' => $bill_due_date, 'subtotal' => $bill_upyog, 'shipping' => $bill_shipping, 'discount' => $bill_disc, 'discountr' => $bill_discr, 'tax' => $bill_tax, 'total' => $bill_yog, 'notes' => $bill_notes, 'status' => $bill_status, 'csd' => $bill_csd, 'eid' => $eid);
        $status = $db->insert('returnc', $dataArray)->rStatus();
        foreach ($_POST['bill_saman'] as $key => $value) {
            $item_product = $value;
            $prid = $_POST['bill_pid'][$key];
            $item_qty = $_POST['saman_qty'][$key];
            $item_price = $_POST['billsaman_price'][$key];
            $item_tax = $_POST['bill_saman_tax'][$key];
            $item_valuetax = $_POST['saman-tax'][$key];
            $item_subtotal = $_POST['bill_saman_sub'][$key];
            $dataArray = array('tid' => $bill_number, 'product' => $item_product, 'qty' => $item_qty, 'price' => "$item_price", 'discount' => $item_valuetax, 'subtotal' => $item_subtotal, 'trate' => $item_tax, 'pid' => $prid);
            $db->insert('return_items', $dataArray);

            //If you like to add stock relations with returncs    
            /*
            $data = $item_product;
            if (($pos = strpos($data, "_")) !== false) {
                $prid = substr($data, $pos + 1);
                $query = "UPDATE products SET qty = qty - $item_qty WHERE pid='$prid'";
                $aBindWhereParam=array('pid'=>$prid);
                $db->pdoQuery($query,$aBindWhereParam)->results();
            }
            */

        }

        header('Content-Type: application/json');
        if ($status == true) {

            echo json_encode(array('status' => 'Success', 'message' =>
                "Return Receipt has been created successfully! &nbsp; &nbsp;<a href='view/return-view.php?id=$bill_number' class='btn btn-warning btn-lg'><span class='icon-print' aria-hidden='true'></span> Print </a> &nbsp; <a href='view/return-view.php?id=$bill_number&download=1' class='btn btn-info btn-lg'><span class='icon-download2' aria-hidden='true'></span> Download </a>"));


        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }
        break;

    case "edit_quote" :
        if ($user->group_id <= 2) {
            $bill_tax = 0;
            $id = $_POST["update_id"];
            $bill_number = $_POST['invoice_id'];
            $bill_date = date('Y-m-d', strtotime($_POST['tsn_date']));
            $bill_due_date = date('Y-m-d', strtotime($_POST['tsn_due']));
            $bill_upyog = $_POST['bill_upyog'];
            $bill_shipping = $_POST['saman_deliv'];
            $bill_disc = $_POST['bill_fdisc'];
            $bill_discr = $_POST['invoice_vvt'];
            if (isset($_POST['bill_ptax'])) {
                $bill_tax = $_POST['bill_ptax'];
            }
            $bill_yog = $_POST['bill_yog'];
            $bill_notes = $_POST['invoice_notes'];
            $bill_status = $_POST['invoice_status'];
            $cidd = $_POST['grahak_id'];


            $dataArray = array('tsn_date' => $bill_date, 'tsn_due' => $bill_due_date, 'subtotal' => $bill_upyog, 'shipping' => $bill_shipping, 'discount' => $bill_disc, 'discountr' => $bill_discr, 'tax' => $bill_tax, 'total' => $bill_yog, 'notes' => $bill_notes, 'status' => $bill_status, 'csd' => $cidd);
            if (!is_numeric($bill_yog)) {
                die();
            }
            $aWhere = array('tid' => $id);
            $status = $db->update('returnc', $dataArray, $aWhere)->rStatus();

            $grahak_name = $_POST['grahak_name'];
            $grahak_adrs1 = $_POST['grahak_adrs1'];
            $grahak_adrs2 = $_POST['grahak_adrs2'];
            $grahak_phone = $_POST['grahak_phone'];
            $grahak_email = $_POST['grahak_email'];
            $grahak_tax = $_POST['grahak_tax'];


            if ($cidd == 0) {

                $aWhere = array('tid' => $id);
                $db->delete('return_customers', $aWhere);


                $dataArray = array('tid' => $bill_number, 'name' => $grahak_name, 'address1' => $grahak_adrs1, 'address2' => $grahak_adrs2, 'phone' => $grahak_phone, 'email' => $grahak_email, 'taxid' => $grahak_tax);
                $isnr = $db->insert('return_customers', $dataArray)->rStatus();
            }

            $aWhere = array('tid' => $id);
            $db->delete('return_items', $aWhere);
            foreach ($_POST['bill_saman'] as $key => $value) {
                $item_product = $value;

                $item_qty = $_POST['saman_qty'][$key];
                $prid = $_POST['bill_pid'][$key];
                $item_price = $_POST['billsaman_price'][$key];
                $item_tax = $_POST['bill_saman_tax'][$key];
                $item_valuetax = $_POST['saman-tax'][$key];
                $item_subtotal = $_POST['bill_saman_sub'][$key];


                $dataArray = array('tid' => $id, 'product' => $item_product, 'qty' => $item_qty, 'price' => "$item_price", 'discount' => $item_valuetax, 'subtotal' => $item_subtotal, 'trate' => $item_tax, 'pid' => $prid);
                $db->insert('return_items', $dataArray);

            }


            header('Content-Type: application/json');

            if ($status == true) {

                echo json_encode(array('status' => 'Success', 'message' =>
                    "Return Receipt has been updated successfully! &nbsp; &nbsp;<a href='view/return-view.php?id=$bill_number' class='btn btn-warning btn-lg'><span class='icon-print' aria-hidden='true'></span> Print </a> &nbsp; <a href='view/return-view.php?id=$bill_number&download=1' class='btn btn-info btn-lg'><span class='icon-download2' aria-hidden='true'></span> Download </a>"));


            } else {

                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }


        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'You are not authorized for this action. Action reverted!!'));
        }
        break;

    case "delete_quote" :
        if ($user->group_id <= 2) {


            $id = $_POST["delete"];


            $aWhere = array('tid' => $id);
            $limit = 'LIMIT 1';
            $status = $db->delete('returnc', $aWhere, $limit)->affectedRows();
            $aWhere = array('tid' => $id);
            $db->delete('return_items', $aWhere);
            $aWhere = array('tid' => $id);
            $db->delete('return_customers', $aWhere, $limit);


            if ($status == 1) {
                echo json_encode(array('status' => 'Success', 'message' =>
                    'Return Receipt has been deleted successfully!'));

            } else {

                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }


        }
        break;


}


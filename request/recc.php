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
		$bill_tax = 0;
        $bill_number = intval($_POST['invoice_id']);
        $bill_date = date('Y-m-d', strtotime($_POST['tsn_date']));
        $bill_due_date = date('Y-m-d', strtotime($_POST['tsn_due']));
        $bill_upyog = $_POST['bill_upyog'];
        $bill_shipping = $_POST['saman_deliv'];
        $bill_disc = $_POST['bill_fdisc'];
        if (isset($_POST['bill_ptax'])) {
            $bill_tax = $_POST['bill_ptax'];
			$bill_tax2 = $_POST['bill_ptax2'];
        }
        $bill_yog = $_POST['bill_yog'];
        $bill_notes = $_POST['invoice_notes'];
        // $bill_status = $_POST['invoice_status'];
        $bill_csd = $_POST['grahak_id'];
        if (isset($_POST['crepeat'])) {
            $crepeat = $_POST['crepeat'];
        }
        $bill_discr = $_POST['invoice_vvt'];
        $paym = $_POST['payment'];
        $bill_pm = 0;
        $pay = '';
        if ($paym == 'card') {
            $bill_status = 'due';
            $bill_pm = 1;
            $pay = "&nbsp; &nbsp;<a href='index.php?rdp=checkout-rec&id=$bill_number' class='btn btn-primary btn-lg'><span class='icon-credit-card' aria-hidden='true'></span>Make Payment </a>";
        }
        $rperiod = $_POST['invoice_re'];


        switch ($rperiod) {
            case 1:
                $rc_next = date("Y-m-d", strtotime("+7 days", strtotime($bill_due_date)));
                break;
            case 2:
                $rc_next = date("Y-m-d", strtotime("+15 days", strtotime($bill_due_date)));
                break;
            case 3:
                $rc_next = date("Y-m-d", strtotime("+30 days", strtotime($bill_due_date)));
                break;
            case 4:
                $rc_next = date("Y-m-d", strtotime("+3 months", strtotime($bill_due_date)));
                break;
            case 5:
                $rc_next = date("Y-m-d", strtotime("+6 months", strtotime($bill_due_date)));
                break;
            case 6:
                $rc_next = date("Y-m-d", strtotime("+1 years", strtotime($bill_due_date)));
                break;
            case 7:
                $rc_next = date("Y-m-d", strtotime("+3 years", strtotime($bill_due_date)));
                break;
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
                $dbg = $db->insert('rec_customers', $dataArray);
            }

        if (!is_numeric($bill_yog)) {
            die();
        }
        $dataArray = array('tid' => $bill_number, 'tsn_date' => $bill_date, 'tsn_due' => $bill_due_date, 'subtotal' => $bill_upyog, 'shipping' => $bill_shipping, 'discount' => $bill_disc, 'discountr' => $bill_discr, 'tax' => $bill_tax, 'tax2' => $bill_tax2, 'total' => $bill_yog, 'notes' => $bill_notes, 'csd' => $bill_csd, 'eid' => $eid, 'pmethod' => $bill_pm, 'rperiod' => $rperiod, 'rc_next' => $rc_next);
        $status = $db->insert('rec_invoices', $dataArray)->rStatus();
        foreach ($_POST['bill_saman'] as $key => $value) {
            $item_product = $value;
            $prid = $_POST['bill_pid'][$key];
            $item_qty = $_POST['saman_qty'][$key];
            $item_price = $_POST['billsaman_price'][$key];
            $item_tax = $_POST['bill_saman_tax'][$key];
			$item_tax2 = $_POST['bill_saman_tax2'][$key];
            $item_valuetax = $_POST['saman-tax'][$key];
            $item_subtotal = $_POST['bill_saman_sub'][$key];
            $dataArray = array('tid' => $bill_number, 'product' => $item_product, 'qty' => $item_qty, 'price' => $item_price, 'discount' => $item_valuetax, 'subtotal' => $item_subtotal, 'trate' => $item_tax, 'trate2' => $item_tax2, 'pid' => $prid);
            $db->insert('rec_items', $dataArray);

            //If you like to add stock relations with quotes    
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
                "Recurring Invoice has been created successfully!  $pay&nbsp; &nbsp;<a href='index.php?rdp=viewinvoice&id=$bill_number' class='btn btn-info btn-lg'><span class='icon-file-text2' aria-hidden='true'></span> View </a>&nbsp; &nbsp;<a href='view/invoice-rec.php?id=$bill_number' class='btn btn-warning btn-lg'><span class='icon-print' aria-hidden='true'></span> Print </a> &nbsp; <a href='view/invoice-rec.php?id=$bill_number&download=1' class='btn btn-info btn-lg'><span class='icon-download2' aria-hidden='true'></span> Download </a>"));


        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }
        break;
    case "edit_quote" :
        if ($user->group_id <= 2) {

            $id = $_POST["update_id"];
            $bill_tax = 0;
			$bill_tax2 = 0;
            $bill_number = $_POST['invoice_id'];
            $bill_date = date('Y-m-d', strtotime($_POST['tsn_date']));
            $bill_due_date = date('Y-m-d', strtotime($_POST['tsn_due']));
            $bill_upyog = $_POST['bill_upyog'];
            $bill_shipping = $_POST['saman_deliv'];
            $bill_disc = $_POST['bill_fdisc'];
            if (isset($_POST['bill_ptax'])) {
                $bill_tax = $_POST['bill_ptax'];
				$bill_tax2 = $_POST['bill_ptax2'];
            }
            $bill_yog = $_POST['bill_yog'];
            $bill_notes = $_POST['invoice_notes'];
            //  $bill_status = $_POST['invoice_status'];
            $cidd = $_POST['grahak_id'];
            $crepeat = isset($_POST['crepeat']);
            $paym = $_POST['payment'];
            $pay = '';
            $bill_pm = 0;
            if ($paym == 'card') {
                $bill_status = 'due';
                $bill_pm = 1;
                $pay = "&nbsp; &nbsp;<a href='index.php?rdp=checkout&id=$bill_number' class='btn btn-primary btn-lg'><span class='icon-credit-card' aria-hidden='true'></span>Make Payment </a>";
            }
            $rperiod = $_POST['invoice_re'];


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


            $dataArray = array('tsn_date' => $bill_date, 'tsn_due' => $bill_due_date, 'subtotal' => $bill_upyog, 'shipping' => $bill_shipping, 'discount' => $bill_disc, 'tax' => $bill_tax, 'tax2' => $bill_tax2, 'total' => $bill_yog, 'notes' => $bill_notes, 'csd' => $cidd, 'pmethod' => $bill_pm, 'rperiod' => $rperiod, 'rc_next' => $rc_next);
            if (!is_numeric($bill_yog)) {
                die();
            }
            $aWhere = array('tid' => $id);
            $status = $db->update('rec_invoices', $dataArray, $aWhere)->rStatus();

            $grahak_name = $_POST['grahak_name'];
            $grahak_adrs1 = $_POST['grahak_adrs1'];
            $grahak_adrs2 = $_POST['grahak_adrs2'];
            $grahak_phone = $_POST['grahak_phone'];
            $grahak_email = $_POST['grahak_email'];
            $grahak_tax = $_POST['grahak_tax'];


            if ($cidd == 0) {

                $aWhere = array('tid' => $id);
                $db->delete('rec_customers', $aWhere);


                $dataArray = array('tid' => $bill_number, 'name' => $grahak_name, 'address1' => $grahak_adrs1, 'address2' => $grahak_adrs2, 'phone' => $grahak_phone, 'email' => $grahak_email, 'taxid' => $grahak_tax);
                $isnr = $db->insert('rec_customers', $dataArray)->rStatus();
            }

            $aWhere = array('tid' => $id);
            $db->delete('rec_items', $aWhere);
            foreach ($_POST['bill_saman'] as $key => $value) {
                $item_product = $value;
                $prid = $_POST['bill_pid'][$key];
                $item_qty = $_POST['saman_qty'][$key];
                $item_price = $_POST['billsaman_price'][$key];
                $item_subtotal = $_POST['bill_saman_sub'][$key];
                $item_tax = $_POST['bill_saman_tax'][$key];
				$item_tax2 = $_POST['bill_saman_tax2'][$key];
                $item_valuetax = $_POST['saman-tax'][$key];
                $dataArray = array('tid' => $id, 'product' => $item_product, 'qty' => $item_qty, 'price' => $item_price, 'discount' => $item_valuetax, 'subtotal' => $item_subtotal, 'trate' => $item_tax, 'trate2' => $item_tax2, 'pid' => $prid);
                $db->insert('rec_items', $dataArray);


            }


            header('Content-Type: application/json');

            if ($status == true) {

                echo json_encode(array('status' => 'Success', 'message' =>
                    "Recurring Invoice has been updated successfully! $pay&nbsp; &nbsp;<a href='index.php?rdp=viewinvoice&id=$bill_number' class='btn btn-info btn-lg'><span class='icon-file-text2' aria-hidden='true'></span> View </a>&nbsp; &nbsp;<a href='view/invoice-rec.php?id=$bill_number' class='btn btn-warning btn-lg'><span class='icon-print' aria-hidden='true'></span> Print </a> &nbsp; <a href='view/invoice-rec.php?id=$bill_number&download=1' class='btn btn-info btn-lg'><span class='icon-download2' aria-hidden='true'></span> Download </a>"));


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
            $status = $db->delete('rec_invoices', $aWhere, $limit)->affectedRows();
            $aWhere = array('tid' => $id);
            $db->delete('rec_items', $aWhere);
            $aWhere = array('tid' => $id);
            $db->delete('rec_customers', $aWhere, $limit);


            if ($status == 1) {
                echo json_encode(array('status' => 'Success', 'message' =>
                    'Recurring Invoice has been deleted successfully!'));

            } else {

                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }


        }
        break;


}


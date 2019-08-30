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
if ($user->group_id <= 2) {


    $act = isset($_POST['act']) ? $_POST['act'] : "";
    switch ($act) {


        case "delete_invoice" :
            $id = intval($_POST["delete"]);
            $limit = 'LIMIT 1';
            $aWhere = array('tid' => $id);
            $status = $db->delete('rec_invoices', $aWhere, $limit)->affectedRows();
            $aWhere = array('tid' => $id);
            $db->delete('rec_items', $aWhere);
            $db->delete('rec_part_trans', $aWhere);
            $db->delete('rec_customers', $aWhere, $limit);


            if ($status == 1) {

                echo json_encode(array('status' => 'Success', 'message' =>
                    'Invoice has been deleted successfully!'));

            } else {

                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }

            break;


        case "update_invoice" :
            $id = intval($_POST["update_id"]);
            $bill_number = $_POST['invoice_id'];
            $bill_date = date('Y-m-d', strtotime($_POST['tsn_date']));
            $bill_due_date = date('Y-m-d', strtotime($_POST['tsn_due']));
            $bill_upyog = $_POST['bill_upyog'];
            $bill_shipping = $_POST['saman_deliv'];
            $bill_disc = $_POST['bill_disc'];
            $bill_tax = isset($_POST['bill_tax']);
            $bill_yog = $_POST['bill_yog'];
            $bill_notes = $_POST['invoice_notes'];
            $bill_status = $_POST['invoice_status'];
            $cidd = $_POST['grahak_id'];
            $paym = $_POST['payment'];
            $pay = '';
            $bill_pm = 0;
            if ($paym == 'card') {
                $bill_status = 'due';
                $bill_pm = 1;
                $pay = "&nbsp; &nbsp;<a href='index.php?rdp=checkout&id=$bill_number' class='btn btn-primary btn-lg'><span class='icon-credit-card' aria-hidden='true'></span>Make Payment </a>";
            }

            $dataArray = array('tsn_date' => $bill_date, 'tsn_due' => $bill_due_date, 'subtotal' => $bill_upyog, 'shipping' => $bill_shipping, 'discount' => $bill_disc, 'tax' => $bill_tax, 'total' => $bill_yog, 'notes' => $bill_notes, 'status' => $bill_status, 'csd' => $cidd, 'pmethod' => $bill_pm);
            if (!is_numeric($bill_yog)) {
                die();
            }
            $aWhere = array('tid' => $id);
            $status = $db->update('invoices', $dataArray, $aWhere)->rStatus();
            $grahak_name = $_POST['grahak_name'];
            $grahak_adrs1 = $_POST['grahak_adrs1'];
            $grahak_adrs2 = $_POST['grahak_adrs2'];
            $grahak_phone = $_POST['grahak_phone'];
            $grahak_email = $_POST['grahak_email'];
            $grahak_tax = $_POST['grahak_tax'];

            if ($cidd == 0) {


                $aWhere = array('tid' => $id);
                $db->delete('customers', $aWhere);


                $dataArray = array('tid' => $bill_number, 'name' => $grahak_name, 'address1' => $grahak_adrs1, 'address2' => $grahak_adrs2, 'phone' => $grahak_phone, 'email' => $grahak_email, 'taxid' => $grahak_tax);
                $db->insert('customers', $dataArray);
            }

            $aWhere = array('tid' => $id);
            $db->delete('invoice_items', $aWhere);
            foreach ($_POST['bill_saman'] as $key => $value) {
                $item_product = $value;

                $pitem_qty = $_POST['psaman_qty'][$key];
                $item_qty = $_POST['saman_qty'][$key];
                $item_price = $_POST['billsaman_price'][$key];
                $item_discount = $_POST['bill_saman_discount'][$key];
                $item_subtotal = $_POST['bill_saman_sub'][$key];
                $dqty = $item_qty - $pitem_qty;
                $dataArray = array('tid' => $id, 'product' => $item_product, 'qty' => $item_qty, 'price' => $item_price, 'discount' => $item_discount, 'subtotal' => $item_subtotal);
                $db->insert('invoice_items', $dataArray);
                if (($pos = strpos($item_product, "_")) !== false) {
                    $prid = substr($item_product, $pos + 1);

                    $query = "UPDATE products SET qty = qty - $dqty WHERE pid='$prid'";

                    $aBindWhereParam = array('pid' => $prid);
                    $db->pdoQuery($query, $aBindWhereParam)->results();
                }

            }


            header('Content-Type: application/json');

            if ($status == true) {

                echo json_encode(array('status' => 'Success', 'message' =>
                    "Invoice has been updated successfully! $pay&nbsp; &nbsp;<a href='index.php?rdp=view-invoice&id=$bill_number' class='btn btn-info btn-lg'><span class='icon-file-text2' aria-hidden='true'></span> View </a>&nbsp; &nbsp;<a href='view/invoice-view.php?id=$bill_number' class='btn btn-warning btn-lg'><span class='icon-print' aria-hidden='true'></span> Print </a> &nbsp; <a href='view/invoice-view.php?id=$bill_number&download=1' class='btn btn-info btn-lg'><span class='icon-download2' aria-hidden='true'></span> Download </a>"));


            } else {

                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again. ' . $query));
            }


            break;

    }


} else {

    echo json_encode(array('status' => 'Error', 'message' =>
        'You are not authorized for this action. Action reverted!!'));
}

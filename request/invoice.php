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
$crepeat = '';
$bill_tax = 0;
$bill_tax2 = 0;
$bill_number = $_POST['invoice_id'];
$bill_date = date('Y-m-d', strtotime($_POST['tsn_date']));
$bill_due_date = date('Y-m-d', strtotime($_POST['tsn_due']));

$bill_shipping = $_POST['saman_deliv'];
$bill_disc = $_POST['bill_fdisc'];
$bill_discr = $_POST['invoice_vvt'];
if (isset($_POST['bill_ptax'])) {
    $bill_tax = $_POST['bill_ptax'];
	$bill_tax2 = $_POST['bill_ptax2'];
}
$bill_upyog = $_POST['bill_upyog'];

$bill_yog = $_POST['bill_yog'];
$bill_notes = $_POST['invoice_notes'];
$bill_status = $_POST['invoice_status'];
$bill_csd = $_POST['grahak_id'];
if (isset($_POST['crepeat'])) {
    $crepeat = $_POST['crepeat'];
}
$query = "";
$bill_pm = 0;
$paym = $_POST['payment'];
$pay = '';
if ($paym == 1) {
    $bill_status = 'due';

    $pay = "&nbsp; &nbsp;<a href='index.php?rdp=checkout&id=$bill_number' class='btn btn-primary btn-lg'><span class='icon-credit-card' aria-hidden='true'></span>Make Payment </a>";
}


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
        $db->insert('customers', $dataArray);
    }
if (!is_numeric($bill_yog)) {
    die();
}

$dataArray = array('tid' => $bill_number, 'tsn_date' => $bill_date, 'tsn_due' => $bill_due_date, 'subtotal' => $bill_upyog, 'shipping' => $bill_shipping, 'discount' => $bill_disc, 'discountr' => $bill_discr, 'tax' => $bill_tax, 'tax2' => $bill_tax2, 'total' => $bill_yog, 'notes' => $bill_notes, 'status' => $bill_status, 'csd' => $bill_csd, 'eid' => $eid, 'pmethod' => $paym, 'ramm' => 0);
$dt = $db->insert('invoices', $dataArray);
if (($paym == 4) AND ($bill_status == 'paid')) {

    $dt = $db->select('conf')->results();
    $acid = $dt['acid'];
    $dataArray = array('tno' => "INV" . $bill_number, 'acid' => $acid, 'amount' => $bill_yog, 'stat' => 'Cr', 'tdate' => $bill_date, 'note' => "Sales invoice #$bill_number");
    $status = $db->insert('ac_transactions', $dataArray)->rStatus();
    if ($status == true) {

        $query = "UPDATE bank_ac SET lastbal = lastbal + $bill_yog WHERE id='$acid'";
        $aBindWhereParam = array('id' => $acid);
        $db->pdoQuery($query, $aBindWhereParam)->results();
    }

}
$realvalue = 0;
$salvalue = 0;
foreach ($_POST['bill_saman'] as $key => $value) {
    $item_product = $value;
    $item_qty = $_POST['saman_qty'][$key];
    $prid = $_POST['bill_pid'][$key];
    $item_price = $_POST['billsaman_price'][$key];
    $item_tax = $_POST['bill_saman_tax'][$key];
    $item_valuetax = $_POST['saman-tax'][$key];
	$item_tax2 = $_POST['bill_saman_tax2'][$key];
    $item_valuetax2 = $_POST['saman-tax2'][$key];
    $item_subtotal = $_POST['bill_saman_sub'][$key];
    if ($siteConfig['vinc'] == 1) {
        $pittax = $item_valuetax / $item_qty;
        //$item_price=$item_price-$pittax;
        // $item_price=floatval($item_price);
        //$item_price=str_replace(',', '', $item_price);
    }
    $dataArray = array('tid' => $bill_number, 'product' => $item_product, 'qty' => $item_qty, 'price' => "$item_price", 'discount' => $item_valuetax, 'subtotal' => $item_subtotal, 'trate' => $item_tax, 'trate2' => $item_tax2, 'pid' => $prid);
    $db->insert('invoice_items', $dataArray);

    $data = $item_product;
    if ($prid > 0) {

        $query = "UPDATE products SET qty = qty - $item_qty WHERE pid='$prid'";
        $aBindWhereParam = array('pid' => $prid);
        $db->pdoQuery($query, $aBindWhereParam)->results();
        $row = $db->select('products', null, $aBindWhereParam)->results();
        $fproduct_price = $row['fproduct_price'];
        $realvalue += $fproduct_price * $item_qty;
        $salvalue += $item_subtotal;
    }

}
$poutcum = 0;
$poutcum = $salvalue - $realvalue;
$rresult = $poutcum;

$wdataArray = array('tid' => $bill_number, 'pdate' => $bill_date, 'poutcum' => $rresult);
$query = "INSERT INTO `paic` (`id`, `tid`, `pdate`, `poutcum`) VALUES (NULL, '$bill_number', '$bill_date', '$poutcum');";
$aBindWhereParam = array('tid' => $bill_number);
$db->pdoQuery($query, $aBindWhereParam)->results();

header('Content-Type: application/json');

echo json_encode(array('status' => 'Success', 'message' =>
    "Invoice has been created successfully! $pay&nbsp; &nbsp;<a href='index.php?rdp=view-invoice&id=$bill_number' class='btn btn-info btn-lg'><span class='icon-file-text2' aria-hidden='true'></span> View </a>&nbsp; &nbsp;<a href='view/invoice-view.php?id=$bill_number' class='btn btn-warning btn-lg'><span class='icon-print' aria-hidden='true'></span> Print </a> &nbsp; <a href='view/invoice-view.php?id=$bill_number&download=1' class='btn btn-info btn-lg'><span class='icon-download2' aria-hidden='true'></span> Download </a>"));


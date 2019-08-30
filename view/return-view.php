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
header("Content-type: text/html; charset=UTF-8");

//view or download invoice
require_once("../includes/config.php");
if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    $token = "";
}
$getID = intval($_GET['id']);
$validtoken = hash_hmac('ripemd160', $getID, IKEY);

require_once("../includes/system.php");
if (!$user->isSigned()) {
    if ($validtoken !== $token) {
        die('<h3>Invalid Token!</h3>');
    }
}

include_once '../lib/pdowrapper/class.pdowrapper.php';

// database connection setings
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$db = new PdoWrapper($dbConfig);

$query = "SELECT qterms,footer FROM billing_terms";
$row_conf = $db->select('billing_terms')->results();
$terms = $row_conf['qterms'];
$footer = $row_conf['footer'];
require_once("../includes/system.php");
require_once("../includes/globalcf.php");
$query = "SELECT p.*, i.*
			FROM return_items p 
			JOIN returnc i ON i.tid = p.tid			
			WHERE p.tid = '$getID'";

$result = $db->pdoQuery($query)->results();
foreach ($result as $row) {
    $cidd = intval($row['csd']);
    $bill_number = $row['tid'];
    $bill_date = dateformat($row['tsn_date']);
    $bill_due_date = dateformat($row['tsn_due']);
    $bill_upyog = $row['subtotal'];
    $bill_shipping = $row['shipping'];
    $bill_disc = $row['discount'];
    $bill_tax = $row['tax'];
    $bill_yog = $row['total'];
    $bill_notes = $row['notes'];
    $bill_status = $row['status'];
    if ($cidd > 0) {

        $whr = array('id' => $cidd);
        $row1 = $db->select('reg_customers', null, $whr)->results();
        $grahak_name = $row1['name'];
        $grahak_email = $row1['email'];
        $grahak_adrs1 = $row1['address1'];
        $grahak_adrs2 = $row1['address2'];
        $grahak_phone = $row1['phone'];
        $grahak_tax = $row1['taxid'];
    } else {

        $whr = array('tid' => $getID);
        $row1 = $db->select('return_customers', null, $whr)->results();
        $grahak_name = $row1['name'];
        $grahak_email = $row1['email'];
        $grahak_adrs1 = $row1['address1'];
        $grahak_adrs2 = $row1['address2'];
        $grahak_phone = $row1['phone'];
        $grahak_tax = $row1['taxid'];
    }
}
$grahak_postcode = "";
include('../includes/expresst.php');
//Create a new instance
$pdf = new ExperssInvoice();
//Set number formatting
$pdf->currencyFormat($siteConfig['fcurr']);
//Bill Title
$title = 'Return Receipt';
$totitle = 'Return By';
$pdf->billTitle($title, $totitle);
//invoice no
$pdf->billNo("#" . $siteConfig['pref'] . $bill_number);
//Set date
$pdf->billingDate($bill_date);
//Set due date
$pdf->dueDate($bill_due_date);
$pdf->currF($siteConfig['curr']);
//Biller address
$pdf->biller($siteConfig['name'], $siteConfig['address'], $siteConfig['address2'],
    "Phone: " . $siteConfig['phone'], "Mail: " . $siteConfig['email'], "GST TIN " .
    $siteConfig['vatno']);
//Customer address
$pdf->payee($grahak_name, $grahak_adrs1, $grahak_adrs2, "Phone: " . $grahak_phone,
    $grahak_email, $grahak_tax);
$pdf->SetTitle('Damage Receipt');
$pdf->AddPage();
//print biller,customer details
$pdf->Party();
//iproduct list
$query = "SELECT * FROM return_items 
			WHERE tid = '$getID' ORDER BY id ASC";

$resultp = $db->pdoQuery($query)->results();
$pdf->BillBody($resultp);
//total
$pdf->BillTotal(array(
    $bill_upyog,
    $bill_disc,
    $bill_tax,
    $bill_shipping,
    $bill_yog,
    $bill_notes,
    $bill_status));

//billing terms
$pdf->Terms($terms);
$pdf->FooterNote($footer);
//billing output
if (isset($_GET['download'])) {
    $pdf->Output($bill_number . '_' . $grahak_name . '.pdf', 'D');
}
$pdf->Output($bill_number . '_' . $grahak_name . '.pdf', 'I');


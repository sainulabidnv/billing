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

if(isset($_GET['id'])) {
	$getID = intval($_GET['id']);
	$ustype = 'Customer';
	$cstable = 'reg_customers';
	$transtable = 'part_trans';
	$invtable = 'invoices';

} else if(isset($_GET['vid'])) { 
	$getID = intval($_GET['vid']); 
	$ustype = 'Vendor';
	$cstable = 'reg_vendors'; 
	$transtable = 'receipt_trans';
	$invtable = 'receipts';
	
}
else die('Wrong!');


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
require_once("../includes/system.php");
require_once("../includes/globalcf.php");
include('../includes/payments.php');

//======Customer Query

	
	$whr = array('id' => $getID);
	$row1 = $db->select($cstable, null, $whr)->results();
	$user_name = $row1['name'];
	$user_email = $row1['email'];
	$user_phone = $row1['phone'];
	$pdf = new ExperssInvoice();
	$title = $siteConfig['name'].' - '.$ustype.' Report';
	$totitle = $user_name.', Ph: '.$user_phone.', Email: '.$user_email;
	$pdf->billTitle($title, $totitle);
	$pdf->AddPage();
	
	if(isset($_GET['f']) and isset($_GET['t']) and date('Y-m-d',strtotime($_GET['f'])) < date('Y-m-d',strtotime($_GET['t'])) ) {
		$pdatewhere = "($transtable.tdate BETWEEN '".date('Y-m-d',strtotime($_GET['f']))."' AND '".date('Y-m-d',strtotime($_GET['t']))."') AND";
		$idatewhere = "(tsn_date BETWEEN '".date('Y-m-d',strtotime($_GET['f']))."' AND '".date('Y-m-d',strtotime($_GET['t']))."') AND";
		 
		} else { $pdatewhere =''; $idatewhere =''; }
	
	$pquery = "SELECT 1 AS type,  $invtable.tid AS id, $transtable.tdate AS date, SUM($transtable.amount) AS credit, $transtable.note AS note FROM $invtable INNER JOIN $transtable ON $invtable.tid = $transtable.tid  WHERE $pdatewhere $invtable.csd = $getID GROUP BY $transtable.tdate ORDER BY $transtable.tdate DESC";
	$iquery = "SELECT 2 AS type, tid AS id, tsn_date AS date, status AS note, total AS debit FROM $invtable WHERE $idatewhere csd = $getID ORDER BY tsn_date ASC";
	
	$payments = $db->pdoQuery($pquery)->results();
	$invoices = $db->pdoQuery($iquery)->results();
	
	function cmp($payments, $invoices){
		$pd = strtotime($payments['date']);
		$id = strtotime($invoices['date']);
		return ($pd-$id);
	}
	
	$results = array_merge($payments, $invoices);
	usort($results, 'cmp');




$pdf->BillBody($results);

if (isset($_GET['d']) and $_GET['d']==1) {
    $pdf->Output($user_name . '_' . date('d-m-Y') . '.pdf', 'D');
}
$pdf->Output($user_name . '_' . date('d-m-Y') . '.pdf', 'I');


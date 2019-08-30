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
include_once('../lib/pdowrapper/class.pdowrapper.php');
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$db = new PdoWrapper($dbConfig);
if (isset($_POST["send"])) {
$id = intval($_POST["send"]);
} else $id=0;

if (isset($_POST["csd"])) {
    $paym = intval($_POST["csd"]);
} else {
    $paym = 0;
}
global $user, $siteConfig;


include_once('../includes/system.php');
if (!$user->isSigned()) {
    die('log in');
}

$act = isset($_POST['act']) ? $_POST['act'] : "";
switch ($act) {
    
	
	case "deletePayment" :
	
	if(isset($_POST['type']) and isset($_POST['date'])){
		
		$date = $_POST['date'];
		
		if($_POST['type'] == 'customer' ){ 
		$tbltrans = 'part_trans';
		$tbltinvo = 'invoices';
		}
		
		if($_POST['type'] == 'vendor' ){ 
		$tbltrans = 'receipt_trans';
		$tbltinvo = 'receipts';
		}
		
		//Customer
		$query = "SELECT * FROM $tbltrans WHERE date(tdate)='$date'"; 
        $results = $db->pdoQuery($query)->results();
		foreach ($results as $result) {
			
			$tid = $result["tid"];
			$amount = $result["amount"];
			$upquery = "UPDATE $tbltinvo SET ramm = ramm - $amount , status = IF(ramm > $amount, 'partial', 'due')  WHERE tid='$tid'";
			$db->pdoQuery($upquery)->results();
			
			$db->delete($tbltrans,  array('id' => $result["id"]));
			
			
			}
		echo json_encode(array('status' => 'Success', 'message' =>  "Payment has been successfully Deleted"));
			
		
		
		
		//Vendor
		
		
		}
	
	
	break;
	
	case "receiptPayment" :
	
	if ($id > 0) {

        $query = "SELECT total,ramm FROM receipts WHERE csd=$id ORDER BY tid DESC";

        $result = $db->pdoQuery($query)->results();

        if ($result) {

            $total = $db->sum('receipts', 'total', "csd=$id ");
            $paid = $db->sum('receipts', 'ramm', "csd=$id ");
		}
		
		$due = $total-$paid;
		$part = $_POST["part"];
        $tnote = $_POST["tnote"];
		$tdate = date('Y-m-d',strtotime($_POST["date"]));
		if($due<$part) {echo json_encode(array('status' => 'Error', 'message' => 'Exceed amount')); break;}
		
		$query2 = "SELECT total,ramm,id,tid FROM receipts WHERE status != 'paid'  AND csd=$id ORDER BY tid ASC";	
		$result2 = $db->pdoQuery($query2)->results();
		$test='';
		foreach($result2 as $rslt) {
			
			$pending = $rslt['total']-$rslt['ramm'];
			$id = $rslt['id'];
			$tid = $rslt['tid'];
			
			if($part>=$pending) {  $nramm = $pending; $stt = 'paid'; } else { $nramm = $part; $stt = 'partial'; }
				$upquery = "UPDATE receipts SET ramm = ramm + $nramm , status = '$stt' WHERE id='$id'";
				$db->pdoQuery($upquery)->results();
				//$tod = date("Y-m-d");
				$dataArray = array('tid' => $tid, 'amount' => "$nramm", 'note' => "$tnote", 'tdate' => $tdate);
				$status = $db->insert('receipt_trans', $dataArray)->rStatus();
			
			if($part>$pending) { $part=$part-$pending; } else { break; }

			}
			
			if (isset($status)) {
            echo json_encode(array('status' => 'Success', 'message' =>
                "Payment has been successfully Credited"));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => "something went wrong!"));

        }
			
			
			
	
	}
	
	break;
	
	case "customPayment" :
	
	if ($id > 0) {

        $query = "SELECT total,ramm FROM invoices WHERE csd=$id ORDER BY tid DESC";

        $result = $db->pdoQuery($query)->results();

        if ($result) {

            $total = $db->sum('invoices', 'total', "csd=$id ");
            $paid = $db->sum('invoices', 'ramm', "csd=$id ");
		}
		
		$due = $total-$paid;
		$part = $_POST["part"];
        $tnote = $_POST["tnote"];
		$tdate = date('Y-m-d',strtotime($_POST["date"]));
		if($due<$part) {echo json_encode(array('status' => 'Error', 'message' => 'Exceed amount')); break;}
		
		$query2 = "SELECT total,ramm,id,tid FROM invoices WHERE (status = 'due' || status = 'partial' ) AND csd=$id ORDER BY tid ASC";	
		$result2 = $db->pdoQuery($query2)->results();
		$test='';
		foreach($result2 as $rslt) {
			
			$pending = $rslt['total']-$rslt['ramm'];
			$id = $rslt['id'];
			$tid = $rslt['tid'];
			
			if($part>=$pending) {  $nramm = $pending; $stt = 'paid'; } else { $nramm = $part; $stt = 'partial'; }
				$upquery = "UPDATE invoices SET ramm = ramm + $nramm , status = '$stt' WHERE id='$id'";
				$db->pdoQuery($upquery)->results();
				//$tod = date("Y-m-d");
				$dataArray = array('tid' => $tid, 'amount' => "$nramm", 'note' => "$tnote", 'tdate' => $tdate);
				$status = $db->insert('part_trans', $dataArray)->rStatus();
			
			if($part>$pending) { $part=$part-$pending; } else { break; }

			}
			
			if (isset($status)) {
            echo json_encode(array('status' => 'Success', 'message' =>
                "Payment has been successfully Credited"));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => "something went wrong!"));

        }
			
			
			
	
	}
	break;
	
	
	case "mark_payment" :
        if ($id > 0) {

            $whereConditions = array('tid' => $id);
			
            $row = $db->select('invoices', array('total','ramm'), $whereConditions)->results();
			$total = $row['total'];
			$ramm = $row['ramm'];
			if($ramm < $total) { 
			$nramm = $total-$ramm; 
			$query = "UPDATE invoices SET ramm = ramm + $nramm , status = 'paid' WHERE tid='$id'";
			
            $aBindWhereParam = array('tid' => $id);
			$db->pdoQuery($query, $aBindWhereParam)->results();
            $tod = date("Y-m-d");
			$dataArray = array('tid' => $id, 'amount' => "$nramm", 'note' => 'Mark as paid', 'tdate' => $tod);
			$status = $db->insert('part_trans', $dataArray)->rStatus();
			}
			

            if ($paym == 4) {
                
                $dt = $db->select('conf')->results();
                $acid = $dt['acid'];
                $tod = date("Y-m-d");
                $dataArray = array('tno' => "INV" . $id, 'acid' => $acid, 'amount' => $total, 'stat' => 'Cr', 'tdate' => $tod, 'note' => "Sales invoice #$id");
                $status = $db->insert('ac_transactions', $dataArray)->rStatus();
                if ($status == true) {

                    $query = "UPDATE bank_ac SET lastbal = lastbal + $total WHERE id='$acid'";
                    $aBindWhereParam = array('id' => $acid);
                    $db->pdoQuery($query, $aBindWhereParam)->results();
                }

            }

        }


//send the message, check for errors
        if ($status == true) {
            echo json_encode(array('status' => 'Success', 'message' =>
                "Invoice #$id has been successfully marked as Paid"));
        } else {
            echo json_encode(array('status' => 'Error', 'message' => "Invoice #$id has been already marked as Paid"));

        }

        break;
    case "part_payment" :
        if ($id > 0) {
            $part = $_POST["part"];
            $tnote = $_POST["tnote"];
			$tdate = date('Y-m-d',strtotime($_POST["date"]));

            $colm = array('total', 'status', 'ramm');
            $whereConditions = array('tid' => $id);
            $result1 = $db->select('invoices', $colm, $whereConditions)->results();
            $total = $result1['total'];
            $status = $result1['status'];
            $rmm = $result1['ramm'];
            $ctotal = $part + $rmm;
            
			if ($ctotal > $total) { echo json_encode(array('status' => 'Error', 'message' => 'Exceed amount')); break;}
			if ($ctotal < $total) {
                $pstatus = 'partial';

            } else {

                $pstatus = 'paid';
            }
            $query = "UPDATE invoices SET ramm = ramm + $part , status = '$pstatus', pmethod = '2' WHERE tid='$id'";
            $aBindWhereParam = array('tid' => $id);
            $db->pdoQuery($query, $aBindWhereParam)->results();
            //$tod = date("Y-m-d");
            $dataArray = array('tid' => $id, 'amount' => $part, 'note' => $tnote, 'tdate' => $tdate);
            $status = $db->insert('part_trans', $dataArray)->rStatus();
            if ($status == true) {
                echo json_encode(array('status' => 'Success', 'message' =>
                    'Payment has been added successfully!'));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }
        }


        break;
}
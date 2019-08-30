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
if (!$user->group_id == 1) {
    die('log in');
}
$act = isset($_POST['act']) ? $_POST['act'] : "";
switch ($act) {
    case "update_company" :
        $cname = $_POST['company_name'];
        $address = $_POST['c_address'];
        $address2 = $_POST['c_address2'];
        $contact = $_POST['numberc'];
        $mail = $_POST['emailc'];


        $dataArray = array('cname' => $cname, 'address' => $address, 'address2' => $address2, 'phone' => $contact, 'email' => $mail);

        $status = $db->update('panel', $dataArray, array('id' => 0))->rStatus();


        header('Content-Type: application/json');

        if ($status == true) {
            echo json_encode(array('status' => 'Success', 'message' =>
                'Company Settings have been updated successfully!'));

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }

        break;
    case "update_billing" :
        $crncy = $_POST['currency'];
        $fcrncy = $_POST['fcurrency'];
        $pref = $_POST['prefix'];
        $cvtno = $_POST['cvatno'];
        if (isset($_POST['vstat'])) {
            $vatst = 1;
        } else {
            $vatst = 0;
        }
        $vrate = $_POST['vrat'];
		$vrate2 = $_POST['vrat2'];
        if (isset($_POST['vinc'])) {
            $vinc = 1;
        } else {
            $vinc = 0;
        }


        $dataArray = array('cvatno' => $cvtno, 'vatr' => $vrate,'vatr2' => $vrate2, 'vatst' => $vatst, 'vinc' => $vinc, 'crncy' => $crncy, 'fcrncy' => $fcrncy, 'pref' => $pref);

        $status = $db->update('panel', $dataArray, array('id' => 0))->rStatus();

        if ($status == true) {
            echo json_encode(array('status' => 'Success', 'message' =>
                'Billing Settings have been updated successfully!'));

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }

        break;
    case "update_date" :
        $dformat = $_POST['dformat'];
        $dzone = $_POST['dzone'];

        $query = "UPDATE panel SET dfomat = ?, zone = ?";
        $dataArray = array('dfomat' => $dformat, 'zone' => $dzone);

        $status = $db->update('panel', $dataArray, array('id' => 0))->rStatus();


        if ($status == true) {
            echo json_encode(array('status' => 'Success', 'message' =>
                'Date and Time zone Settings have been updated successfully!'));

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }
        break;
    case "update_smtp" :
        $host = $_POST['host'];
        $port = $_POST['port'];
        $auth = $_POST['auth'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $sender = $_POST['sender'];

        $qdel = "DELETE FROM sys_smtp LIMIT 1; ";
        $db->pdoQuery($qdel)->results();
        $dataArray = array('Host' => $host, 'Port' => $port, 'Auth' => $auth, 'Username' => $username, 'password' => $password, 'Sender' => $sender);
        $status = $db->insert('sys_smtp', $dataArray)->rStatus();


        if ($status == true) {
            echo json_encode(array('status' => 'Success', 'message' =>
                'Email SMTP Settings have been updated successfully!'));

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }
        break;
    case "payment_gateway" :
        $pemal = $_POST['pemail'];
        $stripe_sk = $_POST['stripe_sk'];
        $stripe_pk = $_POST['stripe_pk'];
        $ccode = $_POST['ccode'];
        $ppmail = $_POST['paypalmail'];
        $ppenable = $_POST['enablepaypal'];
        $paypalcurr = $_POST['paypalcurr'];


        $data = $db->update('payment', array('optvalue' => $stripe_sk), array('id' => 1));
        $data = $db->update('payment', array('optvalue' => $stripe_pk), array('id' => 2));
        $data = $db->update('payment', array('optvalue' => $pemal), array('id' => 3));
        $data = $db->update('payment', array('optvalue' => $ccode), array('id' => 4));
        $data = $db->update('payment', array('optvalue' => $ppenable), array('id' => 5));
        $data = $db->update('payment', array('optvalue' => $ppmail), array('id' => 6));
        $data = $db->update('payment', array('optvalue' => $paypalcurr), array('id' => 7));


        echo json_encode(array('status' => 'Success', 'message' =>
            "Payment Gateway Settings have been updated successfully $ccode !"));
        break;
    case "update_terms" :
        $iterms = $_POST['terms'];
        $qterms = $_POST['qterms'];
        $rterms = $_POST['rterms'];
        $ifooter = $_POST['footer'];

        $dataArray = array('terms' => $iterms, 'qterms' => $qterms, 'rterms' => $rterms, 'footer' => $ifooter);
        $aWhere = array('id' => 1);
        $status = $db->update('billing_terms', $dataArray, $aWhere)->rStatus();


        if ($status == true) {
            echo json_encode(array('status' => 'Success', 'message' =>
                'Billing Terms have been updated successfully!'));

        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }
        break;

}
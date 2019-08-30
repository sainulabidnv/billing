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
$id = $_POST["inid"];
$csd = $_POST["csd"];
$raj = 768;
$cmail = $_POST["eadd"];
$message = $_POST["message"];
global $user, $siteConfig;

$query = "SELECT cname FROM panel";
$row2 = $db->select('panel', array('cname'))->results();
$comp = $row2['cname'];
include_once('../includes/system.php');
if (!$user->isSigned()) {
    die('log in');
}
if ($csd > 0) {

    $colm = array('name', 'email');
    $whereConditions = array('id' => $csd);
    $result1 = $db->select('reg_customers', $colm, $whereConditions)->results();
} else {

    $colm = array('name', 'email');
    $whereConditions = array('tid' => $id);
    $result1 = $db->select('customers', $colm, $whereConditions)->results();
}

$cname = $result1['name'];
//$cmail = $result1['email'];


$row2 = $db->select('sys_smtp')->results();
$host = $row2['Host'];
$port = $row2['Port'];
$auth = $row2['Auth'];
$username = $row2['Username'];
$password = $row2['password'];
$sender = $row2['Sender'];
$token = hash_hmac('ripemd160', $id, IKEY);
include_once('../lib/mailer/PHPMailerAutoload.php');

//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = $host;
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = $port;
//Whether to use SMTP authentication
$mail->SMTPAuth = $auth;
//Username to use for SMTP authentication
$mail->Username = $username;
//Password to use for SMTP authentication
$mail->Password = $password;
//Set who the message is to be sent from
$mail->setFrom($sender, $comp);
//Set who the message is to be sent to
$mail->addAddress($cmail, $cname);
//Set the subject line
$mail->Subject = "Customer Invoice $id Successfully Generated";
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($message);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo json_encode(array('status' => 'Error', 'message' =>
        "Customer Invoice $id Successfully Generated $message"));
} else {
    echo json_encode(array('status' => 'Success', 'message' => "Invoice #$id has been successfully sent on <strong>$cmail</strong> to <strong>$cname</strong>"));

}

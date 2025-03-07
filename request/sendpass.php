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
$siteConfig['name'] = "Send Password Reset Code";
$umail = isset($_GET['mail']) ? $_GET['mail'] : "";
if (!$umail == "") {
    include("../includes/config.php");
    require_once("../includes/system.php");
    include('../includes/header-login.php');

    include_once('../lib/pdowrapper/class.pdowrapper.php');
    $dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
    $db = new PdoWrapper($dbConfig);

    global $user, $siteConfig;


    $row2 = $db->select('sys_smtp')->results();
    $host = $row2['Host'];
    $port = $row2['Port'];
    $auth = $row2['Auth'];
    $username = $row2['Username'];
    $password = $row2['password'];
    $sender = $row2['Sender'];
    $query = "SELECT username,confirmation FROM users WHERE email='$umail'";
    $whereConditions = array('email' => $umail);

    if ($row3 = $db->select('employee', null, $whereConditions)->results()) {
        $uname = $row3['username'];
        $ccode = $row3['confirmation'];
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
        $mail->setFrom($sender, 'Password Reset');
//Set who the message is to be sent to
        $mail->addAddress($umail, $uname);
//Set the subject line
        $mail->Subject = 'Password reset request';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
        $mail->msgHTML("<div><strong>Dear $uname,</strong><br>
<p>This is a notice that a password reset code request generated for you.</p>
<h3>Rest Code is </h3><br>$ccode<br><br>Additionally, you can use this link to reset password<br><a href='" .
            SITE . "/request/password.php?c=$ccode'>" . SITE . "/request/password.php?c=$ccode</a><br>If this request is not generated by you, just ignore the mail.<br>Thanks");
//Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');


        ?>
        <div id="my-tab-content" class="tab-content">


        <div class="tab-pane active" id="login">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">

                        <img class="text-center profile-img" src="images/logo.jpg"
                             alt="">
                    </div>
                    <div class="modal-body ">
                        <?php

                        if (!$mail->send()) {
                            echo "<div style='text-align: center;'><h2>Sorry! </h2>There is a connection error to email code, check you email settings.</div>";
                        } else {
                            echo "<div style='text-align: center;'><h2>Success</h2>Reset code has been successfully send on the registered email. Please check your email.</div>";
                        }
                        ?>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div></div><?php
    }
}

?>
                        
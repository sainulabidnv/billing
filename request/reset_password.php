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
include("../includes/config.php");
include("../includes/system.php");

if (count($_POST)) {
    $input = new \Invoice\Express\Collection($_POST);
    $res = $user->resetPassword($input->email);
    $errorMessage = '';
    $confirmMessage = '';

    if ($res) {
        $url = SITE . '/request/password.php';
        $emailurl = SITE . '/request/sendpass.php?mail=' . $input->email;
        $confirmMessage = "A password reset code has generated. You can ask the superadmin for reset code and use below link to change your password.<br><br><a href='{$url}' class='btn btn-lg btn-success btn-block'>Change Password</a><br><br> Additionally, you can send this code to your registered email. <br><a href='{$emailurl}' class='btn btn-lg btn-success btn-block'>Email Reset Code</a><br>";
        echo json_encode(array(
            'error' => $user->log->getErrors(),
            'confirm' => $confirmMessage,
            'form' => $user->log->getFormErrors(),
        ));

    } else {
        $errorMessage = $user->log->getErrors();
        $errorMessage = $errorMessage[0];

        echo json_encode(array('confirm' =>
            "Sorry! There is no account associated with this email."));
    }


}

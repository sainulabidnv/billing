<?php
include('../includes/system.php');

//Process password change
if (count($_POST)) {
    /*
    * Covert POST into a Collection object
    * for better handling
    */
    $input = new \Invoice\Express\Collection($_POST);

    $hash = $input->c;

    if (!$user->isSigned() and $hash) {
        //Change password with confirmation hash
        $user->newPassword($hash, array(
            'password' => $input->password,
            'Password2' => $input->Password2,
        ));
        $redirectPage = "login";
    } else {
        //Change the password of signed in user without a confirmation hash
        $user->update(array(
            'password' => $input->password,
            'Password2' => $input->Password2,
        ));
        $redirectPage = 'account';
    }

    echo json_encode(array(
        'error' => $user->log->getAllErrors(),
        'confirm' => 'Password Changed',
        'form' => $user->log->getFormErrors(),
    ));
}

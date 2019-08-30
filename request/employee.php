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
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = 0;
}

switch ($op) {
    case "login":
        login();
        break;
    case "logout":
        logout();
        break;
    case "register":
        register();
        break;
    case "update":
        update();
        break;
    case "changepass":
        changepass();
        break;

}
function login()
{
    include('../includes/system.php');

    //Process Login
    if (count($_POST)) {
        /*
        * Covert POST into a Collection object
        * for better value handling
        */
        $input = new \Invoice\Express\Collection($_POST);
        $input->filter('username', 'password');
        $user->login($input->username, $input->password, $input->auto);

        $errMsg = '';

        if ($user->log->hasError()) {
            $errMsg = $user->log->getErrors();
            $errMsg = $errMsg[0];
        }
        if ($user->isSigned()) {
            echo json_encode(array(
                'error' => $user->log->getErrors(),
                'confirm' => "You are now login as <b>$user->username</b>",
                'form' => $user->log->getFormErrors(),
            ));
        } else {
            echo json_encode(array(

                'error' => $user->log->getErrors(),
                'confirm' => "Empty!!",
                'form' => $user->log->getFormErrors(),

            ));
        }
    }
}

function logout()
{
    include('../includes/system.php');

    $user->logout();

    redirect("../");
}

function register()
{
    include('../includes/system.php');
    include('../includes/validations.php');

    //Process Registration
    if (count($_POST)) {
        /*
        * Covert POST into a Collection object
        * for better values handling
        */
        $input = new \Invoice\Express\Collection($_POST);

        /*
        * If the form fields names match your DB columns then you can reduce the collection
        * to only those expected fields using the filter() function
        */
        $input->filter('username', 'first_name', 'last_name', 'email', 'phone',
            'password', 'Password2', 'group_id');

        /*
        * Register the user
        * The register method takes either an array or a Collection
        */
        $newUser = $user->manageUser();
        $newUser->register($input);

        echo json_encode(array(
            'error' => $user->log->getErrors(),
            'confirm' => 'User Registered Successfully. You may login now!',
            'form' => $user->log->getFormErrors(),
        ));
    }
}

function update()
{
    include('../includes/system.php');
    include('../includes/validations.php');

    //Process Update
    if (count($_POST)) {
        /*
        * Covert POST into a Collection object
        * for better value handling
        */
        $input = new \Invoice\Express\Collection($_POST);

        /*
        * Updates queue
        */
        foreach ($input->toArray() as $name => $val) {
            if (is_null($user->getProperty($name))) {
                /*
                * If the field is not part of the user properties
                * then reject the update
                */
                unset($input->$name);
            } else {
                /*
                * If the value is the same as the tha value stored
                * on the user properties then reject the update
                */
                if ($user->$name == $val) {
                    unset($input->$name);
                }

            }
        }

        if (!$input->isEmpty()) {
            //Update info
            $user->update($input->toArray());
        } else {
            //Nothing has changed
            $user->log->error('No need to update!');
        }

        echo json_encode(array(
            'error' => $user->log->getErrors(),
            'confirm' => 'Account Updated!',
            'form' => $user->log->getFormErrors(),
        ));
    }
}

function changepass()
{
    include('../includes/system.php');

    //Process password change
    if (count($_POST)) {
        /*
        * Covert POST into a Collection object
        * for better handling
        */
        $input = new \Invoice\Express\Collection($_POST);

        $hash = $input->c;
        $plainTextPassword = $input->Currentpassword;

        if (!$user->isSigned() and $hash) {
            //Change password with confirmation hash
            $user->newPassword($hash, array(
                'password' => $input->password,
                'Password2' => $input->Password2,
            ));
            echo json_encode(array(
                'error' => $user->log->getAllErrors(),
                'confirm' => 'Password Changed',
                'form' => $user->log->getFormErrors(),
            ));
            $redirectPage = "login";
        } else {
            $hash = new \Invoice\Express\Hash();
            if ($user->password === $hash->generateUserPassword($user, $plainTextPassword)) {
                // Current password match

                $user->update(array(
                    'password' => $input->password,
                    'Password2' => $input->Password2,
                ));
                echo json_encode(array(
                    'error' => $user->log->getAllErrors(),
                    'confirm' => 'Password Changed',
                    'form' => $user->log->getFormErrors(),
                ));
            } else {
                echo json_encode(array(
                    'error' => $user->log->getAllErrors(),
                    'confirm' => 'Incorrect Current Password',
                    'form' => $user->log->getFormErrors(),
                ));
            }
        }


    }
}

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
//SMS GETWAY API SETUP FILE

################### DO NOT START EDIT FROM HERE  ##############################
######################################################################
//databaseand connection and login check
include_once('../includes/config.php');
include_once('../lib/pdowrapper/class.pdowrapper.php');
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$db = new PdoWrapper($dbConfig);
global $user, $siteConfig;

$query = "SELECT cname FROM panel";
$row2 = $db->select('panel', array('cname'), null)->results();
$comp = $row2['cname'];
include_once('../includes/system.php');
if (!$user->isSigned()) {
    die('log in');
}


//customer details via GET method
$id = $_POST["send"];
$csd = $_POST["csd"];

//customer phone name details
if ($csd > 0) {
    $query1 = "SELECT name,phone FROM reg_customers WHERE id='$csd'";
    $whereConditions = array('id' => $csd);
    $row = $db->select('reg_customers', array('name,phone'), $whereConditions)->results();
} else {

    $whereConditions = array('tid' => $id);
    $row = $db->select('customers', array('name,phone'), $whereConditions, 'ORDER BY id DESC')->results();
}

$cname = $row['name'];
$cphone = $row['phone'];


// Customer name is
$customername = $cname;
//Customer mobile no is
$customernumber = $cphone;

##############################################################
#######################START EDIT FROM HERE#####################
////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////                             STARTS FROM HERE                   //////////////////////////

//////////////////////////////              PUT SMS API CODE  HERE         //////////////////////////


/////////////////////   JSON RESPONSE SAMPLE CODE FOR RESULT  //////////////////////////
/* if (!$sms->send()) {
echo json_encode(array(
'status' => 'Error',
'message'=> 'There has been an error, please try again.'	    	
));
} else {
echo json_encode(array(
'status' => 'Success',
'message'=> "Invoice #$id has been successfully sent on <strong>$customernumber</strong> to <strong>$cname</strong>"
));

}
*/


//////////////////////// DELETE THIS CODE BLOCK AFTER PLACING API CODE    /////////////////

////DELETE HERE
echo json_encode(array('status' => 'Error', 'message' =>
    'There is no sms gateway setup found. Please setup an SMS gateway to send SMS.'));
//// TO HERE


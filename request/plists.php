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
 * *********************************************************************
 */
include_once('../includes/config.php');

include_once('../includes/system.php');
if (!$user->isSigned()) {
    die();
}
include_once('../lib/pdowrapper/class.pdowrapper.php');
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$db = new PdoWrapper($dbConfig);


$search = strip_tags(trim($_GET['q']));
$data = array();
// Do Prepared Query 
$query = "SELECT pid,product_name,product_price,product_code,tax FROM services WHERE CONCAT(product_name,product_code) LIKE '%" . $search .
    "%' LIMIT 20";

$result = $db->pdoQuery($query)->results();


foreach ($result as $row) {
    $data[] = array('id' => $row['pid'], 'text' => $row['product_name'] . '-' . $row['product_code'] . '|' . $row['product_price'] . '|' . $row['tax']);
}

// return the result in json
echo json_encode($data);
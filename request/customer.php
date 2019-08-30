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
include_once('../includes/config.php');

include_once('../includes/system.php');
if (!$user->isSigned()) {
    die();
}
include_once('../lib/pdowrapper/class.pdowrapper.php');
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$db = new PdoWrapper($dbConfig);


if (!empty($_POST["keyword"])) {
    $query = "SELECT * FROM reg_customers WHERE CONCAT(name,phone) like '%" . $_POST["keyword"] .
        "%' ORDER BY name LIMIT 0,20";
    $result = $db->pdoQuery($query)->results();
    if (!empty($result)) {

        foreach ($result as $row) {
            $cname = $row["name"];
            $cphone = $row["phone"];

            echo ' <tr>
				
					<td>' . $row["name"] . '</td>
				    <td>' . $row["address2"] . '</td>
				    <td>' . $row["phone"] . '</td><td>' . $row["email"] . '</td>
				    <td><button class="btn btn-primary grahk-chayn" data-grahk-id="' . $row['id'] .
                '" data-grahk-name="' . $row['name'] . '" data-grahk-phone="' . $row['phone'] .
                '" data-grahk-address-1="' . $row['address1'] . '" data-grahkaddrs2="' . $row['address2'] .
                '" data-grahk-email="' . $row['email'] . '" data-grahk-tax="' . $row['taxid'] . '">Select</button></td>
			    </tr>';
        }


    }
}

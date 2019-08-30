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
// *************************************************************************
if (isset($_GET['page'])) {
    $action = $_GET['page'];
} else {
    die();
}
include('../includes/config.php');
include_once('../includes/system.php');
include_once('../lib/pdowrapper/class.pdowrapper.php');
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$db = new PdoWrapper($dbConfig);
include('../includes/globalcf.php');

global $user;
if ($user->group_id > 2) {
    die();
}
// DB table to use
if ($action == "product") {
    $table = 'products';
    $primaryKey = 'pid';
    $columns = array(
        array('db' => 'product_name', 'dt' => 0),
        array('db' => 'product_price', 'dt' => 1, 'formatter' => function ($d, $row) {
            $out = amountFormat($row["product_price"]);
            return $out;
        }),
        array('db' => 'fproduct_price', 'dt' => 2, 'formatter' => function ($d, $row) {
            $out = amountFormat($row["fproduct_price"]);
            return $out;
        }),
        array('db' => 'qty', 'dt' => 3),
        array(
            'db' => 'pid',
            'dt' => 4,
            'formatter' => function ($d, $row) {
                $out = '<a href="index.php?rdp=product&op=edit&id=' . $row["pid"] .
                    '" class="btn btn-primary btn-xs"><span class="icon-pencil"></span>Edit</a> <a data-product-id="' .
                    $row['pid'] . '" class="btn btn-danger btn-xs delete-product"><span class="icon-bin"></span></a>';
                return $out;
            }
        ));

}

if ($action == "service") {
    $table = 'services';
    $primaryKey = 'pid';
    $columns = array(
        array('db' => 'product_name', 'dt' => 0),
        array('db' => 'product_price', 'dt' => 1, 'formatter' => function ($d, $row) {
            $out = amountFormat($row["product_price"]);
            return $out;
        }),

        array(
            'db' => 'pid',
            'dt' => 2,
            'formatter' => function ($d, $row) {
                $out = '<a href="index.php?rdp=services&op=edit&id=' . $row["pid"] .
                    '" class="btn btn-primary btn-xs"><span class="icon-pencil"></span>Edit</a> <a data-product-id="' .
                    $row['pid'] . '" class="btn btn-danger btn-xs delete-product"><span class="icon-bin"></span></a>';
                return $out;
            }
        ));

}
if ($action == "notes") {
    $table = 'notes';
    $primaryKey = 'id';
    $columns = array(
        array('db' => 'id', 'dt' => 0),
        array('db' => 'title', 'dt' => 1),
        array('db' => 'date', 'dt' => 2),
        array(
            'db' => 'id',
            'dt' => 3,
            'formatter' => function ($d, $row) {
                $out = '<a href="index.php?rdp=notes&op=edit&id=' . $row["id"] .
                    '" class="btn btn-primary btn-xs"><span class="icon-pencil"></span>Edit</a> <a data-note-id="' .
                    $row['id'] . '" class="btn btn-danger btn-xs delete-note"><span class="icon-bin"></span></a>';
                return $out;
            }
        ));

}
$sql_details = array(
    'user' => $dbuser,
    'pass' => $dbpass,
    'db' => $dbname,
    'host' => $dbhost);

require('../includes/ssp.class.php');
echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns));

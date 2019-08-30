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

if (isset($_GET['page'])) {
    $action = $_GET['page'];
} else {
    die();
}
include('../includes/config.php');
include_once('../includes/system.php');
global $user;
$sql_details = array(
    'user' => $dbuser,
    'pass' => $dbpass,
    'db' => $dbname,
    'host' => $dbhost);

if (!$user->isSigned()) {
    die('log in');
}
if ($action == "damage") {

    $table = 'damage';
    $primaryKey = 'id';

    $columns = array(
        array('db' => 't.tid', 'dt' => 0, 'field' => 'tid'),
        array('db' => 't.tsn_date', 'dt' => 1, 'field' => 'tsn_date'),
        array(
            'db' => 'c.name',
            'dt' => 2,
            'field' => 'name',
            'formatter' => function ($d, $row) {

                if ($row['name'] != '') {

                    $out = $row['name'];
                } else {
                    $out = '<span class="label label-info">Unregistered</span>&nbsp;<span class="label label-primary">Walk-in</span>';
                }
                return $out;
            }
        ),
        array(
            'db' => 't.status',
            'dt' => 3,
            'field' => 'status',
            'formatter' => function ($d, $row) {
                if ($row['status'] == "returned") {
                    $out = '<span class="label label-danger">Return Received</span> ';
                } elseif ($row['status'] == "replaced") {
                    $out = '<span class="label label-success">Replaced</span> ';
                } elseif ($row['status'] == "refunded") {
                    $out = '<span class="label label-info">Refunded</span> ';
                }
                return $out;
            }
        ),

        array(
            'db' => 't.tid',
            'dt' => 4,
            'field' => 'tid',
            'formatter' => function ($d, $row) {
                $buttons = '<a href="view/damage-view.php?id=' . $row["tid"] .
                    '" class="btn btn-success btn-xs"><span class="icon-print"></span>Print</a> &nbsp; <a href="index.php?rdp=damage&op=edit&id=' .
                    $row["tid"] . '" class="btn btn-primary btn-xs" title="Edit"><span class="icon-pencil"></span>Edit</a>&nbsp; &nbsp;<a href="view/damage-view.php?id=' .
                    $row["tid"] . '&download=1" class="btn btn-info btn-xs"  title="Download"><span class="icon-download2"></span></a>&nbsp; &nbsp;<a data-quote-id="' .
                    $row['tid'] . '" class="btn btn-danger btn-xs delete-quote"  title="Delete"><span class="icon-bin"></span></a>';
                return $buttons;
            }
        ));

    require('../includes/ssp.customized.class.php');
    $joinQuery = "FROM $table AS t LEFT JOIN reg_vendors AS c ON t.csd=c.id";
    $extraCondition = '';
    echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns,
        $joinQuery));

}
if ($action == "return") {

    $table = 'returnc';
    $primaryKey = 'id';

    $columns = array(
        array('db' => 't.tid', 'dt' => 0, 'field' => 'tid'),
        array('db' => 't.tsn_date', 'dt' => 1, 'field' => 'tsn_date'),
        array(
            'db' => 'c.name',
            'dt' => 2,
            'field' => 'name',
            'formatter' => function ($d, $row) {

                if ($row['name'] != '') {

                    $out = $row['name'];
                } else {
                    $out = '<span class="label label-info">Unregistered</span>&nbsp;<span class="label label-primary">Walk-in</span>';
                }
                return $out;
            }
        ),
        array(
            'db' => 't.status',
            'dt' => 3,
            'field' => 'status',
            'formatter' => function ($d, $row) {
                if ($row['status'] == "returned") {
                    $out = '<span class="label label-danger">Return Received</span> ';
                } elseif ($row['status'] == "replaced") {
                    $out = '<span class="label label-success">Replaced</span> ';
                } elseif ($row['status'] == "refunded") {
                    $out = '<span class="label label-info">Refunded</span> ';
                }
                return $out;
            }
        ),

        array(
            'db' => 't.tid',
            'dt' => 4,
            'field' => 'tid',
            'formatter' => function ($d, $row) {
                $buttons = '<a href="view/return-view.php?id=' . $row["tid"] .
                    '" class="btn btn-success btn-xs"><span class="icon-print"></span>Print</a> &nbsp; <a href="index.php?rdp=return&op=edit&id=' .
                    $row["tid"] . '" class="btn btn-primary btn-xs" title="Edit"><span class="icon-pencil"></span>Edit</a>&nbsp; &nbsp;<a href="view/return-view.php?id=' .
                    $row["tid"] . '&download=1" class="btn btn-info btn-xs"  title="Download"><span class="icon-download2"></span></a>&nbsp; &nbsp;<a data-quote-id="' .
                    $row['tid'] . '" class="btn btn-danger btn-xs delete-quote"  title="Delete"><span class="icon-bin"></span></a>';
                return $buttons;
            }
        ));

    require('../includes/ssp.customized.class.php');
    $joinQuery = "FROM $table AS t LEFT JOIN reg_customers AS c ON t.csd=c.id";
    $extraCondition = '';
    echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns,
        $joinQuery));

}


if ($action == "dilist") {

    $table = 'damage_items';
    $primaryKey = 'id';

    $columns = array(
        array('db' => 'tid', 'dt' => 0),
        array('db' => 'product', 'dt' => 1),
        array('db' => 'qty', 'dt' => 2),
        array('db' => 'price', 'dt' => 3)

    );
    require('../includes/ssp.class.php');
    echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns));


}

if ($action == "ilist") {

    $table = 'return_items';
    $primaryKey = 'id';

    $columns = array(
        array('db' => 'tid', 'dt' => 0),
        array('db' => 'product', 'dt' => 1),
        array('db' => 'qty', 'dt' => 2),
        array('db' => 'price', 'dt' => 3)

    );
    require('../includes/ssp.class.php');
    echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns));


}
//require('../includes/ssp.class.php');
//echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns));



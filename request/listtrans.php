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
include('../includes/config.php');
include_once('../includes/system.php');
global $user;
if ($user->group_id > 2) {
    die();
}
$id = 0;
$sql_details = array(
    'user' => $dbuser,
    'pass' => $dbpass,
    'db' => $dbname,
    'host' => $dbhost);
if (isset($_GET['page'])) {
    $action = 'byac';


} else {
    $action = 'noac';
}

if ($action == "noac") {
    $table = 'ac_transactions';
    $primaryKey = 'id';
    $columns = array(
        array(
            'db' => 't.id',
            'dt' => null,
            'field' => 'id'),
        array(
            'db' => 't.tno',
            'dt' => 0,
            'field' => 'tno',
            'formatter' => function ($d, $row) {
                $out = '<a data-transaction-id="' . $row['id'] .
                    '" class="btn btn-danger btn-xs delete-transaction"><span class="icon-bin"></span></a>&nbsp; ' .
                    $row["tno"] . '';
                return $out;
            }
        ),
        array(
            'db' => 't.amount',
            'dt' => 1,
            'field' => 'amount',
            'formatter' => function ($d, $row) {
                $out = '&nbsp; ' . number_format($row["amount"], 2) . '';
                return $out;
            }
        ),
        array(
            'db' => 'b.acn',
            'dt' => 2,
            'field' => 'acn'),

        array(
            'db' => 't.stat',
            'dt' => 3,
            'field' => 'stat'),
        array(
            'db' => 't.tdate',
            'dt' => 4,
            'field' => 'tdate'),
        array(
            'db' => 't.note',
            'dt' => 5,
            'field' => 'note'));


    require('../includes/ssp.customized.class.php');
    $joinQuery = "FROM ac_transactions AS t LEFT JOIN bank_ac AS b ON t.acid=b.id";
    $extraCondition = '';
    echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns,
        $joinQuery));
}
if ($action == "byac") {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = 0;
    }
    $table = 'ac_transactions';
    $primaryKey = 'id';
    $where = "acid=$id";
    $columns = array(
        array('db' => 'id', 'dt' => null),
        array('db' => 'tno', 'dt' => 0, 'formatter' => function ($d, $row) {
            $out = '<a data-transaction-id="' . $row['id'] .
                '" class="btn btn-danger btn-xs delete-transaction"><span class="icon-bin"></span></a>&nbsp; ' .
                $row["tno"] . '';
            return $out;
        }),
        array('db' => 'amount', 'dt' => 1),
        array('db' => 'stat', 'dt' => 2),
        array('db' => 'tdate', 'dt' => 3),
        array('db' => 'note', 'dt' => 4)
    );


    require('../includes/ssp.class.php');
    echo json_encode(SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $where));
}

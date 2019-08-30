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

$table = 'reg_vendors';
$primaryKey = 'id';

$columns = array(
    array('db' => 'name', 'dt' => 0),
    array('db' => 'address1', 'dt' => 1),
    array('db' => 'phone', 'dt' => 2),
    array(
        'db' => 'id',
        'dt' => 3,
        'formatter' => function ($d, $row) {
            $buttons = '<a href="index.php?rdp=vendor&op=reports&id=' . $row["id"] .
                '" class="btn btn-success btn-xs">Receipts List</a> <a href="index.php?rdp=vendor&op=edit&id=' .
                $row["id"] . '" class="btn btn-primary btn-xs"><span class="icon-pencil"></span>Edit</a> <a data-grahk-id="' .
                $row['id'] . '" class="btn btn-danger btn-xs delete-vendor"><span class="icon-bin"></span></a>';
            return $buttons;
        }
    ));
$sql_details = array(
    'user' => $dbuser,
    'pass' => $dbpass,
    'db' => $dbname,
    'host' => $dbhost);


require('../includes/ssp.class.php');

echo json_encode(SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null));

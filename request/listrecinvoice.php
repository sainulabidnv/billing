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
if (!$user->isSigned()) {
    die();
}
// DB table to use


$sql_details = array(
    'user' => $dbuser,
    'pass' => $dbpass,
    'db' => $dbname,
    'host' => $dbhost);

$table = 'rec_invoices';
$primaryKey = 'id';
$columns = array(
    array(
        'db' => 't.tid',
        'dt' => 0,
        'field' => 'tid'),
    array(
        'db' => 't.tsn_date',
        'dt' => 1,
        'field' => 'tsn_date'),
    array(
        'db' => 't.total',
        'dt' => 2,
        'field' => 'total'
    ),
    array(
        'db' => 'c.name',
        'dt' => 3,
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
        'db' => 't.active',
        'dt' => 4,
        'field' => 'active',
        'formatter' => function ($d, $row) {

            if ($row['active'] == 0) {
                $out = '<span class="label label-primary">Recurring</span> ';
            } else {
                $out = '<span class="label label-warning">Inactive</span> ';
            }
            return $out;
        }
    ),

    array('db' => 't.csd', 'dt' => null, 'field' => 'csd'),
    array(
        'db' => 't.tid',
        'dt' => 5, 'field' => 'tid',
        'formatter' => function ($d, $row) {
            $buttons = '<a href="index.php?rdp=viewinvoice&id=' . $row["tid"] .
                '" class="btn btn-success btn-xs"><span class="icon-file-text2"></span>View</a> &nbsp; <a href="view/invoice-rec.php?id=' .
                $row["tid"] . '&download=1" class="btn btn-info btn-xs"  title="Download"><span class="icon-download2"></span></a>&nbsp; &nbsp;<a data-invoice-id="' .
                $row['tid'] . '" class="btn btn-danger btn-xs rdelete-invoice"  title="Delete"><span class="icon-bin"></span></a>';
            return $buttons;
        }
    ));


require('../includes/ssp.customized.class.php');
$joinQuery = "FROM rec_invoices AS t LEFT JOIN reg_customers AS c ON t.csd=c.id";
$extraCondition = '';
echo json_encode(SSP::isimple($_GET, $sql_details, $table, $primaryKey, $columns,
    $joinQuery));
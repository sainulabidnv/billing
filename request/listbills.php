<?php
/**
 * Express Invoice & Stock Manager
 * Copyright (c) Rajesh Dukiya. All Rights Reserved
 * ***********************************************************************
 *
 *  E-mail: support@skyresoft.com
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
if (!$user->isSigned()) {
    die('log in');
}
if ($action == "quote") {

    $table = 'quote';
    $primaryKey = 'id';

    $columns = array(
        array('db' => 'tid', 'dt' => 0),
        array('db' => 'tsn_date', 'dt' => 1),
        array(
            'db' => 'status',
            'dt' => 2,
            'formatter' => function ($d, $row) {
                if ($row['status'] == "due") {
                    $out = '<span class="label label-danger">Due</span> ';
                } elseif ($row['status'] == "paid") {
                    $out = '<span class="label label-success">Paid</span> ';
                }
                return $out;
            }
        ),
        array('db' => 'csd', 'dt' => null),
        array(
            'db' => 'tid',
            'dt' => 3,
            'formatter' => function ($d, $row) {
                $buttons = '<a href="view/quote-view.php?id=' . $row["tid"] .
                    '" class="btn btn-success btn-xs"><span class="icon-print"></span>Print</a> &nbsp; <a href="index.php?rdp=quote&op=edit&id=' .
                    $row["tid"] . '" class="btn btn-primary btn-xs" title="Edit"><span class="icon-pencil"></span>Edit</a>&nbsp; &nbsp;<a href="view/quote-view.php?id=' .
                    $row["tid"] . '&download=1" class="btn btn-info btn-xs"  title="Download"><span class="icon-download2"></span></a>&nbsp; &nbsp;<a data-quote-id="' .
                    $row['tid'] . '" class="btn btn-danger btn-xs delete-quote"  title="Delete"><span class="icon-bin"></span></a>';
                return $buttons;
            }
        ));

}

if ($action == "receipt") {
    $table = 'receipts';
    $primaryKey = 'id';

    $columns = array(
        array('db' => 'tid', 'dt' => 0),
        array('db' => 'tsn_date', 'dt' => 1),
        array(
            'db' => 'status',
            'dt' => 2,
            'formatter' => function ($d, $row) {
                if ($row['status'] == "due") {
                    $out = '<span class="label label-danger">Due</span> ';
                } elseif ($row['status'] == "paid") {
                    $out = '<span class="label label-success">Paid</span> ';
                }
                return $out;
            }
        ),
        array('db' => 'csd', 'dt' => null),
        array(
            'db' => 'tid',
            'dt' => 3,
            'formatter' => function ($d, $row) {
                $buttons = '<a href="view/receipt-view.php?id=' . $row["tid"] .
                    '" class="btn btn-success btn-xs"><span class="icon-print"></span>Print</a> &nbsp; <a href="index.php?rdp=receipt&op=edit&id=' .
                    $row["tid"] . '" class="btn btn-primary btn-xs" title="Edit"><span class="icon-pencil"></span>Edit</a>&nbsp; &nbsp;<a href="view/receipt-view.php?id=' .
                    $row["tid"] . '&download=1" class="btn btn-info btn-xs"  title="Download"><span class="icon-download2"></span></a>&nbsp; &nbsp;<a data-receipt-id="' .
                    $row['tid'] . '" class="btn btn-danger btn-xs delete-receipt"  title="Delete"><span class="icon-bin"></span></a>';
                return $buttons;
            }
        ));
}
if ($action == "recin") {

    $table = 'rec_invoices';
    $primaryKey = 'id';

    $columns = array(
        array('db' => 'tid', 'dt' => 0),
        array('db' => 'tsn_date', 'dt' => 1),
        array(
            'db' => 'active',
            'dt' => 2,
            'formatter' => function ($d, $row) {
                if ($row['active'] == 0) {
                    $out = '<span class="label label-primary">Recurring</span> ';
                } else {
                    $out = '<span class="label label-warning">Inactive</span> ';
                }
                return $out;
            }
        ),
        array('db' => 'csd', 'dt' => null),
        array(
            'db' => 'tid',
            'dt' => 3,
            'formatter' => function ($d, $row) {
                $buttons = '<a href="index.php?rdp=viewinvoice&id=' . $row["tid"] .
                    '" class="btn btn-info btn-xs"><span class="icon-file-text2"></span>View</a> &nbsp; <a href="view/invoice-rec.php?id=' . $row["tid"] .
                    '" class="btn btn-success btn-xs"><span class="icon-print"></span>Print</a> &nbsp; <a href="index.php?rdp=recurring&op=edit&id=' .
                    $row["tid"] . '" class="btn btn-primary btn-xs" title="Edit"><span class="icon-pencil"></span>Edit</a>&nbsp; &nbsp;<a href="view/invoice-rec.php?id=' .
                    $row["tid"] . '&download=1" class="btn btn-info btn-xs"  title="Download"><span class="icon-download2"></span></a>&nbsp; &nbsp;<a data-invoice-id="' .
                    $row['tid'] . '" class="btn btn-danger btn-xs rdelete-invoice"  title="Delete"><span class="icon-bin"></span></a>';
                return $buttons;
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

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
include_once('../lib/pdowrapper/class.pdowrapper.php');
include_once('../lib/barcode/barcode.php');
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$joinQuery = null;

function barcode($id){ 
$barcode = new barcode_generator();
return $barcode->render_svg('code-39', $id, '');
}

$db = new PdoWrapper($dbConfig);
include('../includes/globalcf.php');
global $user;

// DB table to use
if (isset($_GET['page'])) {
    $action = $_GET['page'];
} else {
    die();
}



$primaryKey = 'id';
if ($action == "income") {
    if ($user->group_id > 2) {
        die();
    }
    $joinQuery = "FROM ac_balance AS b LEFT JOIN reg_customers AS c ON b.uid=c.id";
    $table = 'ac_balance';
    $where = 'b.stat=1 ';
    $columns = array(
        array('db' => 'b.id', 'dt' => null, 'field' => 'id',),
        array(
            'db' => 'b.amount',
            'dt' => 2,
            'field' => 'amount',
            'formatter' => function ($d, $row) {
                 //return clientname(1);
                 return amountFormat($row["amount"]);
            }
        ),
        array('db' => 'b.bdate', 'dt' => 0, 'field' => 'bdate',),
        array('db' => 'c.name', 'dt' => 1, 'field' => 'name',),
        array('db' => 'b.category', 'dt' => 3, 'field' => 'category',),
        array('db' => 'b.note', 'dt' => 4, 'field' => 'note',),
        array(
            'db' => 'b.id',
            'dt' => 5,
            'field' => 'id',
            'formatter' => function ($d, $row) {
                $out = '<a data-btran-id="' . $row['id'] .
                    '" class="btn btn-danger btn-xs delete-btran"><span class="icon-bin"></span></a> ';
                return $out;
            }
        )
        
    );
}
if ($action == "expense") {
    if ($user->group_id > 2) {
        die();
    }
    $joinQuery = "FROM ac_balance AS b LEFT JOIN reg_customers AS c ON b.uid=c.id";
    $table = 'ac_balance';
    $where = 'b.stat=0 ';
    $columns = array(
        array('db' => 'b.id', 'dt' => null, 'field' => 'id',),
        array(
            'db' => 'b.amount',
            'dt' => 2,
            'field' => 'amount',
            'formatter' => function ($d, $row) {
                 //return clientname(1);
                 return amountFormat($row["amount"]);
            }
        ),
        array('db' => 'b.bdate', 'dt' => 0, 'field' => 'bdate',),
        array('db' => 'c.name', 'dt' => 1, 'field' => 'name',),
        array('db' => 'b.category', 'dt' => 3, 'field' => 'category',),
        array('db' => 'b.note', 'dt' => 4, 'field' => 'note',),
        array(
            'db' => 'b.id',
            'dt' => 5,
            'field' => 'id',
            'formatter' => function ($d, $row) {
                $out = '<a data-btran-id="' . $row['id'] .
                    '" class="btn btn-danger btn-xs delete-btran"><span class="icon-bin"></span></a> ';
                return $out;
            }
        )
        
    );
}
if ($action == "employee") {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = 0;
    }
    $table = 'invoices';
    $where = "eid=$id";
    $columns = array(
        array('db' => 'tid', 'dt' => 0),
        array('db' => 'tsn_date', 'dt' => 1),
        array(
            'db' => 'status',
            'dt' => 2,
            'formatter' => function ($d, $row) {
                switch ($row['status']) {
                    case "paid" :
                        $out = '<span class="label label-success">Paid</span> ';
                        break;
                    case "due" :
                        $out = '<span class="label label-danger">Due</span> ';
                        break;
                    case "canceled" :
                        $out = '<span class="label label-warning">Canceled</span> ';
                        break;
                    default :
                        $out = '<span class="label label-info">Pending</span> ';
                        break;
                }
                return $out;
            }
        ),
        array('db' => 'csd', 'dt' => null),
        array(
            'db' => 'tid',
            'dt' => 3,
            'formatter' => function ($d, $row) {
                $buttons = '<a href="index.php?rdp=view-invoice&id=' . $row["tid"] .
                    '" class="btn btn-info btn-xs"><span class="icon-file-text2"></span>View</a> &nbsp; <a href="view/invoice-view.php?id=' . $row["tid"] .
                    '" class="btn btn-success btn-xs"><span class="icon-print"></span>Print</a> &nbsp; <a data-invoice-id="' .
                    $row['tid'] . '" data-csd="' . $row['csd'] .
                    '" class="btn btn-primary btn-xs send-invoice" title="Email"><span class="icon-mail4"></span>Email </a> &nbsp; <a data-invoice-id="' .
                    $row['tid'] . '" data-csd="' . $row['csd'] .
                    '" class="btn btn-info btn-xs send-sms" title="SMS"><span class="icon-star-empty"></span>SMS </a> &nbsp; <a href="index.php?rdp=edit-invoice&id=' .
                    $row["tid"] . '" class="btn btn-warning btn-xs" title="Edit"><span class="icon-pencil"></span>Edit</a>&nbsp; &nbsp;<a href="view/invoice-view.php?id=' .
                    $row["tid"] . '&download=1" class="btn btn-info btn-xs"  title="Download"><span class="icon-download2"></span></a>&nbsp; &nbsp;<a data-invoice-id="' .
                    $row['tid'] . '" class="btn btn-danger btn-xs delete-invoice"  title="Delete"><span class="icon-bin"></span></a>';
                return $buttons;
            }
        ));
}
if ($action == "remployee") {
    if ($user->group_id > 2) {
        die();
    }
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = 0;
    }
    $table = 'rec_invoices';
    $where = "eid=$id";
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

                    $out = '<span class="label label-warning">Canceled</span> ';

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
                    $row["tid"] . '" class="btn btn-warning btn-xs" title="Edit"><span class="icon-pencil"></span>Edit</a>&nbsp; &nbsp;<a href="view/invoice-rec.php?id=' .
                    $row["tid"] . '&download=1" class="btn btn-info btn-xs"  title="Download"><span class="icon-download2"></span></a>&nbsp; &nbsp;<a data-invoice-id="' .
                    $row['tid'] . '" class="btn btn-danger btn-xs rdelete-invoice"  title="Delete"><span class="icon-bin"></span></a>';
                return $buttons;
            }
        ));
}
if ($action == "product") {

    if ($user->group_id > 2) {
        die();
    }
    if (isset($_GET['cat'])) {
        $cat = $_GET['cat'];
    } else {
        $cat = 0;
    }
    $table = 'products';
    $primaryKey = 'pid';
    $where = "pcat=$cat";
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
		array('db' => 'product_code', 'dt' => 4, 'formatter' => function ($d, $row) { return barcode($row["product_code"]);}),
        
        array(
            'db' => 'pid',
            'dt' => 5,
            'formatter' => function ($d, $row) {
                $out = '<a href="index.php?rdp=product&op=edit&id=' . $row["pid"] .
                    '" class="btn btn-primary btn-xs"><span class="icon-pencil"></span>Edit</a> <a data-product-id="' .
                    $row['pid'] . '" class="btn btn-danger btn-xs delete-product"><span class="icon-bin"></span></a> <input type="hidden" value="'.$row["product_code"].'" name="bname[]"> <input style="width:70px;"; type="text" name="bcnt[]" placeholder="barcode" width="2" />';
                return $out;
            }
        ));

}
$sql_details = array(
    'user' => $dbuser,
    'pass' => $dbpass,
    'db' => $dbname,
    'host' => $dbhost);
//require('../includes/ssp.class.php');
//echo json_encode(SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null,$where));
require('../includes/ssp.customized.class.php');

echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where,null));

//simple($request, $sql_details, $table, $primaryKey, $columns, $joinQuery = null,  $extraWhere = '', $groupBy = '')


//complex($request, $conn, $table, $primaryKey, $columns, $whereResult = null,  $whereAll = null)

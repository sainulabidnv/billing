<?php
include_once('../includes/config.php');
include_once('../includes/system.php');
include_once('../lib/pdowrapper/class.pdowrapper.php');
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
$db = new PdoWrapper($dbConfig);
global $user;
include_once("../includes/xlsxwriter.class.php");
if (isset($_POST['op'])) {
    $op = $_POST['op'];
} else {
    $op = '';
}
if ($user->group_id > 2) {
    die();
}
switch ($op) {
    case 'getinvoice' :
        $fdate = date('Y-m-d', strtotime($_POST['fdate']));
        $tdate = date('Y-m-d', strtotime($_POST['tdate']));
        $filename = 'invoices_' . $fdate . '_to_' . $tdate . '.xlsx';
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        $query = "SELECT * FROM invoices WHERE (DATE(tsn_date) BETWEEN '$fdate' AND '$tdate') ORDER BY tid";
        $out = array(array());
        $i = 0;
        if ($result = $db->pdoQuery($query)->results()) {
            foreach ($result AS $row) {
                $tid = $row['tid'];
                $date = $row['tsn_date'];
                $ddate = $row['tsn_due'];
                $samount = $row['subtotal'];
                $ship = $row['shipping'];
                $disc = $row['discount'];
                $tax = $row['tax'];
                $payment = $row['pmethod'];

                $amount = $row['total'];
                $stat = $row['status'];
                $cidd = $row['csd'];


                if ($cidd > 0) {
                    $whereConditions = array('id' => $cidd);
                    $row1 = $db->select('reg_customers', null, $whereConditions)->results();
                    $cname = $row1['name'];


                } else {
                    $whereConditions = array('tid' => $tid);
                    $row1 = $db->select('customers', null, $whereConditions)->results();
                    $cname = $row1['name'];


                }


                $header = array(
                    'Invoice #' => 'string',
                    'Date' => 'date',
                    'Customer' => 'string',
                    'Due Date' => 'date',
                    'Subtotal' => 'string',
                    'Shipping' => 'string',
                    'Discount' => 'string',
                    'Tax' => 'string',
                    'Total' => 'string',
                    'Status' => 'string',
                );


                $result = array($tid, $date, $cname, $ddate, $samount, $ship, $disc, $tax, $amount, $stat);
                $out[$i] = $result;

                $i++;

            }
        }
        $writer = new XLSXWriter();
        $writer->setAuthor('Express Invoice');
        $writer->writeSheet($out, 'Sheet1', $header);

        $writer->writeToStdOut();

        exit(0);
        break;
    case 'getcustomer' :


        $tod = date("Y-m-d");
        $filename = 'customer_data_' . $tod . '.xlsx';
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');


        $query = "SELECT name,address1,address2,phone,email FROM reg_customers";
        $result = $db->pdoQuery($query)->results();


        $header = array(
            'Name' => 'string',
            'Address' => 'string',
            'Address Line 2' => 'string',
            'Phone' => 'string',
            'Email' => 'string',
        );

        $writer = new XLSXWriter();
        $writer->setAuthor('Express Invoice');
        $writer->writeSheet($result, 'Sheet1', $header);

        $writer->writeToStdOut();
        exit(0);
        break;
    case 'unregcustomer' :

        $tod = date("Y-m-d");
        $filename = 'unregisteredcustomer_data_' . $tod . '.xlsx';
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');


        $query = "SELECT name,address1,address2,phone,email FROM customers";
        $result = $db->pdoQuery($query)->results();


        $header = array(
            'Name' => 'string',
            'Address' => 'string',
            'Address Line 2' => 'string',
            'Phone' => 'string',
            'Email' => 'string',
        );

        $writer = new XLSXWriter();
        $writer->setAuthor('Express Invoice');
        $writer->writeSheet($result, 'Sheet1', $header);

        $writer->writeToStdOut();
        exit(0);
        break;
    case 'productlist' :
        $tod = date("Y-m-d");
        $filename = 'productlist_data_' . $tod . '.xlsx';
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');


        $query = "SELECT p.product_name,c.title,p.product_code,p.product_price,p.qty FROM products AS p LEFT JOIN product_cat AS c ON c.id=p.pcat";
        $result = $db->pdoQuery($query)->results();


        $header = array(
            'Product Name' => 'string',
            'Cat' => 'string',
            'Code' => 'string',
            'Price' => 'string',
            'Qty' => 'string',
        );

        $writer = new XLSXWriter();
        $writer->setAuthor('Express Invoice');
        $writer->writeSheet($result, 'Sheet1', $header);

        $writer->writeToStdOut();
        exit(0);
        break;
    case 'salesreports' :

        $tod = date("Y-m-d");
        $filename = 'sales_data_' . $tod . '.xlsx';
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');


        $query = "SELECT mnth,yer,paid,due,unpaid FROM summary";
        $result = $db->pdoQuery($query)->results();


        $header = array(
            'Month' => 'string',
            'Year' => 'string',
            'Paid Amount' => 'string',
            'Due Amount' => 'string',
            'Unpaid invoices' => 'string',
        );

        $writer = new XLSXWriter();
        $writer->setAuthor('Express Invoice');
        $writer->writeSheet($result, 'Sheet1', $header);

        $writer->writeToStdOut();
        exit(0);
        break;
    case 'receipts' :
        $fdate = date('Y-m-d', strtotime($_POST['fdate']));
        $tdate = date('Y-m-d', strtotime($_POST['tdate']));
        $filename = 'invoices_' . $fdate . '_to_' . $tdate . '.xlsx';
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        $query = "SELECT tid,tsn_date,tsn_due,subtotal,shipping,discount,tax,total,status FROM invoices WHERE (DATE(tsn_date) BETWEEN '$fdate' AND '$tdate')";
        $result = $db->pdoQuery($query)->results();
        $header = array(
            'Invoice #' => 'string',
            'Date' => 'date',
            'Due Date' => 'date',
            'Subtotal' => 'string',
            'Shipping' => 'string',
            'Discount' => 'string',
            'Tax' => 'string',
            'Total' => 'string',
            'Status' => 'string',
        );
        $writer = new XLSXWriter();
        $writer->setAuthor('Express Invoice');
        $writer->writeSheet($result, 'Sheet1', $header);

        $writer->writeToStdOut();
        exit(0);
        break;
    case 'quotes' :
        $fdate = date('Y-m-d', strtotime($_POST['fdate']));
        $tdate = date('Y-m-d', strtotime($_POST['tdate']));
        $filename = 'invoices_' . $fdate . '_to_' . $tdate . '.xlsx';
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        $query = "SELECT tid,tsn_date,tsn_due,subtotal,shipping,discount,tax,total,status FROM invoices WHERE (DATE(tsn_date) BETWEEN '$fdate' AND '$tdate')";
        $result = $db->pdoQuery($query)->results();
        $header = array(
            'Invoice #' => 'string',
            'Date' => 'date',
            'Due Date' => 'date',
            'Subtotal' => 'string',
            'Shipping' => 'string',
            'Discount' => 'string',
            'Tax' => 'string',
            'Total' => 'string',
            'Status' => 'string',
        );
        $writer = new XLSXWriter();
        $writer->setAuthor('Express Invoice');
        $writer->writeSheet($result, 'Sheet1', $header);

        $writer->writeToStdOut();
        exit(0);
        break;
    case 'rgetinvoice' :
        $fdate = date('Y-m-d', strtotime($_POST['fdate']));
        $tdate = date('Y-m-d', strtotime($_POST['tdate']));
        $filename = 'recurring_invoices_' . $fdate . '_to_' . $tdate . '.xlsx';
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        $query = "SELECT tid,tsn_date,tsn_due,subtotal,shipping,discount,tax,total,rc_next FROM rec_invoices WHERE (DATE(tsn_date) BETWEEN '$fdate' AND '$tdate')";
        $result = $db->pdoQuery($query)->results();
        $header = array(
            'Invoice #' => 'string',
            'Date' => 'date',
            'First Due Date' => 'date',
            'Subtotal' => 'string',
            'Shipping' => 'string',
            'Discount' => 'string',
            'Tax' => 'string',
            'Total' => 'string',
            'Next Payment' => 'string',
        );
        $writer = new XLSXWriter();
        $writer->setAuthor('Express Invoice');
        $writer->writeSheet($result, 'Sheet1', $header);

        $writer->writeToStdOut();
        exit(0);
        break;

}

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
include_once('../lib/pdowrapper/class.pdowrapper.php');
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$db = new PdoWrapper($dbConfig);
include_once('../includes/globalcf.php');
global $siteConfig, $user;
if ($user->group_id <= 2) {


    $act = isset($_POST['act']) ? $_POST['act'] : "";
    switch ($act) {
        case "create_customer" :
            $grahak_name = $_POST['grahak_name'];
            $grahak_adrs1 = $_POST['grahak_adrs1'];
            $grahak_adrs2 = $_POST['grahak_adrs2'];
            $grahak_phone = $_POST['grahak_phone'];
            $grahak_email = $_POST['grahak_email'];
            $grahak_tax = $_POST['grahak_tax'];
            $tod = date("Y-m-d");
            $dataArray = array('name' => $grahak_name, 'address1' => $grahak_adrs1, 'address2' => $grahak_adrs2, 'phone' => $grahak_phone, 'email' => $grahak_email, 'taxid' => $grahak_tax, 'rdate' => $tod);
            $status = $db->insert('reg_customers', $dataArray)->rStatus();

            if ($status == true) {
                echo json_encode(array('status' => 'Success', 'message' =>
                    'Customer has been created successfully!'));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }

            break;
        case "create_vendor" :
            $grahak_name = $_POST['grahak_name'];
            $grahak_adrs1 = $_POST['grahak_adrs1'];
            $grahak_adrs2 = $_POST['grahak_adrs2'];
            $grahak_phone = $_POST['grahak_phone'];
            $grahak_email = $_POST['grahak_email'];
            $grahak_tax = $_POST['grahak_tax'];

            $dataArray = array('name' => $grahak_name, 'address1' => $grahak_adrs1, 'address2' => $grahak_adrs2, 'phone' => $grahak_phone, 'email' => $grahak_email, 'taxid' => $grahak_tax);
            $status = $db->insert('reg_vendors', $dataArray)->rStatus();

            if ($status == true) {
                echo json_encode(array('status' => 'Success', 'message' =>
                    'Vendor has been created successfully!'));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }


            break;

        case "delete_invoice" :
            $id = intval($_POST["delete"]);
            $limit = 'LIMIT 1';
            $aWhere = array('tid' => $id);
            $status = $db->delete('invoices', $aWhere, $limit)->affectedRows();
            $aWhere = array('tid' => $id);
			  $query2 = "SELECT * FROM invoice_items WHERE tid = '$id'";
        $result2 = $db->pdoQuery($query2)->results();
        foreach ($result2 as $rows) {
            $item_product = $rows['pid'];
			$qty = $rows['qty'];

			 $query = "UPDATE products SET qty = qty + $qty WHERE pid='$item_product'";

                        $aBindWhereParam = array('pid' => $item_product);
                        $db->pdoQuery($query, $aBindWhereParam)->results();

		}
            $db->delete('invoice_items', $aWhere);
            $db->delete('customers', $aWhere, $limit);
            $db->delete('part_trans', $aWhere);
			 $db->delete('paic', $aWhere);


            if ($status == 1) {

                echo json_encode(array('status' => 'Success', 'message' =>
                    'Invoice has been deleted successfully!'));

            } else {

                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }

            break;


        case "update_customer" :
            $getID = intval($_POST['id']);


            $grahak_name = $_POST['grahak_name'];
            $grahak_adrs1 = $_POST['grahak_adrs1'];
            $grahak_adrs2 = $_POST['grahak_adrs2'];
            $grahak_phone = $_POST['grahak_phone'];
            $grahak_email = $_POST['grahak_email'];
            $grahak_tax = $_POST['grahak_tax'];

            $dataArray = array('name' => $grahak_name, 'address1' => $grahak_adrs1, 'address2' => $grahak_adrs2, 'phone' => $grahak_phone, 'email' => $grahak_email, 'taxid' => $grahak_tax);
            $aWhere = array('id' => $getID);
            $status = $db->update('reg_customers', $dataArray, $aWhere)->rStatus();


            if ($status == true) {

                echo json_encode(array('status' => 'Success', 'message' =>
                    'Customer has been updated successfully!'));

            } else {

                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }

            break;
        case "update_invoice" :
            $bill_tax = 0;
            $id = intval($_POST["update_id"]);
            $bill_number = $_POST['invoice_id'];
            $bill_date = date('Y-m-d', strtotime($_POST['tsn_date']));
            $bill_due_date = date('Y-m-d', strtotime($_POST['tsn_due']));

            $bill_shipping = $_POST['saman_deliv'];
            $bill_disc = $_POST['bill_fdisc'];
            $bill_discr = $_POST['invoice_vvt'];
            if (isset($_POST['bill_ptax'])) {
                $bill_tax = $_POST['bill_ptax'];
				$bill_tax2 = $_POST['bill_ptax2'];
            }
            $bill_upyog = $_POST['bill_upyog'];

            $bill_yog = $_POST['bill_yog'];
            $bill_notes = $_POST['invoice_notes'];
            $bill_status = $_POST['invoice_status'];
            $cidd = $_POST['grahak_id'];
            $paym = $_POST['payment'];
            $pay = '';
            //$bill_pm = 0;
            if ($paym == 1) {
                $bill_status = 'due';

                $pay = "&nbsp; &nbsp;<a href='index.php?rdp=checkout&id=$bill_number' class='btn btn-primary btn-lg'><span class='icon-credit-card' aria-hidden='true'></span>Make Payment </a>";
            }

            $dataArray = array('tsn_date' => $bill_date, 'tsn_due' => $bill_due_date, 'subtotal' => "$bill_upyog", 'shipping' => $bill_shipping, 'discount' => $bill_disc, 'discountr' => $bill_discr, 'tax' => $bill_tax, 'tax2' => $bill_tax2, 'total' => $bill_yog, 'notes' => $bill_notes, 'status' => $bill_status, 'csd' => $cidd, 'pmethod' => $paym);
            if (!is_numeric($bill_yog)) {
                die();
            }
            $aWhere = array('tid' => $id);
            $status = $db->update('invoices', $dataArray, $aWhere)->rStatus();
            $grahak_name = $_POST['grahak_name'];
            $grahak_adrs1 = $_POST['grahak_adrs1'];
            $grahak_adrs2 = $_POST['grahak_adrs2'];
            $grahak_phone = $_POST['grahak_phone'];
            $grahak_email = $_POST['grahak_email'];
            $grahak_tax = $_POST['grahak_tax'];

            if ($cidd == 0) {


                $aWhere = array('tid' => $id);
                $db->delete('customers', $aWhere);


                $dataArray = array('tid' => $bill_number, 'name' => $grahak_name, 'address1' => $grahak_adrs1, 'address2' => $grahak_adrs2, 'phone' => $grahak_phone, 'email' => $grahak_email, 'taxid' => $grahak_tax);
                $db->insert('customers', $dataArray);
            }

            $aWhere = array('tid' => $id);
            $db->delete('invoice_items', $aWhere);
			$db->delete('paic', $aWhere);
            $realvalue = 0;
            $salvalue = 0;
            foreach ($_POST['bill_saman'] as $key => $value) {
                $item_product = $value;

                $pitem_qty = $_POST['psaman_qty'][$key];
                $item_qty = $_POST['saman_qty'][$key];
                $prid = $_POST['bill_pid'][$key];
                $item_price = $_POST['billsaman_price'][$key];
                $item_tax = $_POST['bill_saman_tax'][$key];
                $item_valuetax = $_POST['saman-tax'][$key];
				$item_tax2 = $_POST['bill_saman_tax2'][$key];
                $item_valuetax2 = $_POST['saman-tax2'][$key];
                $item_subtotal = $_POST['bill_saman_sub'][$key];
                $dqty = $item_qty - $pitem_qty;


                $dataArray = array('tid' => $id, 'product' => $item_product, 'qty' => $item_qty, 'price' => "$item_price", 'discount' => $item_valuetax, 'subtotal' => $item_subtotal, 'trate' => $item_tax,'trate2' => $item_tax2, 'pid' => $prid);
                $db->insert('invoice_items', $dataArray);
                if ($prid > 0) {


                    $query = "UPDATE products SET qty = qty - $dqty WHERE pid='$prid'";

                    $aBindWhereParam = array('pid' => $prid);
                    $db->pdoQuery($query, $aBindWhereParam)->results();
                    $row = $db->select('products', null, $aBindWhereParam)->results();
                    $fproduct_price = $row['fproduct_price'];
                    $realvalue += $fproduct_price * $item_qty;
                    $salvalue += $item_subtotal;
                }

            }
            if (isset($_POST['restock'])) {

                foreach ($_POST['restock'] as $key => $value) {


                    $myArray = explode('-', $value);
                    $prid = $myArray[0];
                    $dqty = $myArray[1];
                    if ($prid > 0) {
                        $query = "UPDATE products SET qty = qty + $dqty WHERE pid='$prid'";

                        $aBindWhereParam = array('pid' => $prid);
                        $db->pdoQuery($query, $aBindWhereParam)->results();
                    }
                }
            }
            $poutcum = 0;
            $poutcum = $salvalue - $realvalue;
            $rresult = $poutcum;

            $wdataArray = array('tid' => $bill_number, 'pdate' => $bill_date, 'poutcum' => $rresult);
            $query = "INSERT INTO `paic` (`id`, `tid`, `pdate`, `poutcum`) VALUES (NULL, '$bill_number', '$bill_date', '$poutcum');";
            $aBindWhereParam = array('tid' => $bill_number);
            $db->pdoQuery($query, $aBindWhereParam)->results();


            header('Content-Type: application/json');

            if ($status == true) {

                echo json_encode(array('status' => 'Success', 'message' =>
                    "Invoice has been updated successfully! $pay&nbsp; &nbsp;<a href='index.php?rdp=view-invoice&id=$bill_number' class='btn btn-info btn-lg'><span class='icon-file-text2' aria-hidden='true'></span> View </a>&nbsp; &nbsp;<a href='view/invoice-view.php?id=$bill_number' class='btn btn-warning btn-lg'><span class='icon-print' aria-hidden='true'></span> Print </a> &nbsp; <a href='view/invoice-view.php?id=$bill_number&download=1' class='btn btn-info btn-lg'><span class='icon-download2' aria-hidden='true'></span> Download </a>"));


            } else {

                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again. ' . $query));
            }


            break;
        case "delete_customer" :
            $id = intval($_POST["delete"]);


            $limit = 'LIMIT 1';
            $aWhere = array('id' => $id);
            $status = $db->delete('reg_customers', $aWhere, $limit)->affectedRows();


            if ($status == 1) {

                echo json_encode(array('status' => 'Success', 'message' =>
                    'Customer has been deleted successfully!'));

            } else {

                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }
            break;

        case "fileupload" :
            
            $id = intval($_POST["id"]);
            $name = $_POST['name'];
            $dataArray = array('name' => $name, 'inv_id' => $id);
            $status = $db->insert('fileupload', $dataArray)->rStatus();
            if ($status == true) {
                echo json_encode(array('status' => 'Success', 'message' =>
                    'File has been added successfully!'));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }
        break;

        case "filedelete" :
            
            $name = $_POST['name'];
            
            $aWhere = array('name' => $name);
           $db->delete('fileupload', $aWhere);

           

            //$dataArray = array('name' => $name, 'inv_id' => $id);
            if ($status == true) {
                echo json_encode(array('status' => 'Success', 'message' =>
                    'File has been added successfully!'));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }
        break;
        
        case "getfileupload" :
            
            $id = intval($_POST["id"]);
            //$dataArray = array('name' => $name, 'inv_id' => $id);
            //$status = $db->insert('fileupload', $dataArray)->rStatus();

            $query = "SELECT * FROM fileupload WHERE inv_id =". $id ;
            $result = $db->pdoQuery($query)->results();
            $html = '';
            if (!empty($result)) {

                foreach ($result as $row) {
                    
                    $html .= '
                    <tr class="template-download fade in">
                    <td>
                        <span class="preview"> <a href="'.SITE.'images/invoice/'.$row["name"].'" ><img src="'.SITE.'images/invoice/thumbnail/'.$row["name"].'"></a> </span>
                    </td>
                    <td>  </td>
                    <td>  </td>
                    <td>
                        <button class="btn btn-danger delete" data-name="'.$row["name"].'" data-type="DELETE" data-url="'.SITE.'includes/logo.php?file='.$row["name"].'">
                        <i class="glyphicon glyphicon-trash"></i> <span>Delete</span>
                        </button>
                        <input type="checkbox" name="delete" value="1" class="toggle">
                    </td>
                    </tr>
                    ';
                }
            }
            echo json_encode(array('html' => $html));

            break;

    }


} else {

    echo json_encode(array('status' => 'Error', 'message' =>
        'You are not authorized for this action. Action reverted!!'));
}

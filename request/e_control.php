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
include('../includes/globalcf.php');
global $siteConfig, $user;
date_default_timezone_set($siteConfig['zone']);
if ($user->group_id <= 2) {


    $act = isset($_POST['act']) ? $_POST['act'] : "";


    if ($act == 'update_product') {


        $getID = $_POST['id'];
        $product_cat = $_POST['product_cat'];
        $product_name = $_POST['product_name'];
        $product_code = $_POST['product_code'];
        $product_price = $_POST['product_price'];
        $fproduct_price = $_POST['fproduct_price'];
        $product_tax = $_POST['product_tax'];
		$product_tax2 = $_POST['product_tax2'];
        $product_qty = $_POST['product_qty'];


        $dataArray = array('pcat' => $product_cat, 'product_name' => $product_name, 'product_code' => $product_code, 'product_price' => $product_price, 'fproduct_price' => $fproduct_price, 'qty' => $product_qty, 'tax' => $product_tax, 'tax2' => $product_tax2);
        $aWhere = array('pid' => $getID);
        $status = $db->update('products', $dataArray, $aWhere)->rStatus();


        if ($status == true) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Product has been updated successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }


    if ($act == 'delete_product') {


        $id = $_POST["delete"];


        $limit = 'LIMIT 1';
        $aWhere = array('pid' => $id);
        $status = $db->delete('products', $aWhere, $limit)->affectedRows();


        if ($status == 1) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Product has been deleted successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }


    if ($act == 'add_product') {

        $product_name = $_POST['product_name'];
        $product_cat = $_POST['product_cat'];
        $product_code = $_POST['product_code'];
        $product_price = $_POST['product_price'];
        $fproduct_price = $_POST['fproduct_price'];
        $product_qty = intval($_POST['product_qty']);
        $product_tax = $_POST['product_tax'];
		$product_tax2 = $_POST['product_tax2'];


        $dataArray = array('pcat' => $product_cat, 'product_name' => $product_name, 'product_code' => $product_code, 'product_price' => $product_price, 'fproduct_price' => $fproduct_price, 'qty' => $product_qty, 'tax' => $product_tax, 'tax2' => $product_tax2);
        $status = false;
        if ($product_qty >= 0) {
            $status = $db->insert('products', $dataArray)->rStatus();
        }

        header('Content-Type: application/json');


        if ($status == true) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Product has been added successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please check stock units.'));
        }


    }
// notes
    if ($act == 'update_note') {


        $getID = $_POST['id'];
        $product_name = $_POST['title'];
        $product_code = $_POST['note'];
        $product_price = date('Y-m-d', strtotime($_POST['ndate']));;


        $dataArray = array('title' => $product_name, 'note' => $product_code, 'date' => $product_price);
        $aWhere = array('id' => $getID);
        $status = $db->update('notes', $dataArray, $aWhere)->rStatus();


        if ($status == true) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Note has been updated successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }


    if ($act == 'delete_note') {


        $id = $_POST["delete"];


        $limit = 'LIMIT 1';
        $aWhere = array('id' => $id);
        $status = $db->delete('notes', $aWhere, $limit)->affectedRows();


        if ($status == 1) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Note has been deleted successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }


    if ($act == 'add_note') {

        $product_name = $_POST['title'];
        $product_code = $_POST['note'];
        $product_price = date('Y-m-d', strtotime($_POST['ndate']));;


        $dataArray = array('title' => $product_name, 'note' => $product_code, 'date' => $product_price);

        $status = $db->insert('notes', $dataArray)->rStatus();


        header('Content-Type: application/json');


        if ($status == true) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Note has been added successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please check stock units.'));
        }


    }
    //vendor

    if ($act == 'update_vendor') {


        $getID = $_POST['id'];


        $grahak_name = $_POST['grahak_name'];
        $grahak_adrs1 = $_POST['grahak_adrs1'];
        $grahak_adrs2 = $_POST['grahak_adrs2'];
        $grahak_phone = $_POST['grahak_phone'];
        $grahak_email = $_POST['grahak_email'];
        $grahak_tax = $_POST['grahak_tax'];

        $dataArray = array('name' => $grahak_name, 'address1' => $grahak_adrs1, 'address2' => $grahak_adrs2, 'phone' => $grahak_phone, 'email' => $grahak_email, 'taxid' => $grahak_tax);
        $aWhere = array('id' => $getID);
        $status = $db->update('reg_vendors', $dataArray, $aWhere)->rStatus();


        if ($status == true) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Vendor has been updated successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }
    if ($act == 'delete_user') {
        if ($user->group_id == 1) {


            $id = $_POST["delete"];


            $aWhere = array('id' => $id);
            $dataArray = array('activated' => 0);
            $status = $db->update('employee', $dataArray, $aWhere)->rStatus();


            if ($status == true) {

                echo json_encode(array('status' => 'Success', 'message' =>
                    'Employee has been deleted successfully!'));

            } else {

                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }
        }


    }


    if ($act == 'delete_vendor') {


        $id = $_POST["delete"];


        $limit = 'LIMIT 1';
        $aWhere = array('id' => $id);
        $status = $db->delete('reg_vendors', $aWhere, $limit)->affectedRows();


        if ($status == 1) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Vendor has been deleted successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }
} else {

    echo json_encode(array('status' => 'Error', 'message' =>
        'You are not authorized for this action. Action reverted!!'));
}

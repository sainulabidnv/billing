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
include_once('../includes/config.php');
include_once('../includes/system.php');
include_once('../lib/pdowrapper/class.pdowrapper.php');
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$db = new PdoWrapper($dbConfig);
global $siteConfig, $user;
if ($user->group_id <= 2) {


    $act = isset($_POST['act']) ? $_POST['act'] : "";


    if ($act == 'update_product') {


        $getID = $_POST['id'];
        $product_name = $_POST['product_name'];
        $product_code = $_POST['product_code'];
        $product_price = $_POST['product_price'];
        $product_tax = $_POST['product_tax'];


        $dataArray = array('product_name' => $product_name, 'product_code' => $product_code, 'product_price' => $product_price, 'tax' => $product_tax);
        $aWhere = array('pid' => $getID);
        $status = $db->update('services', $dataArray, $aWhere)->rStatus();


        if ($status == true) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Service has been updated successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }


    if ($act == 'delete_product') {


        $id = $_POST["delete"];


        $limit = 'LIMIT 1';
        $aWhere = array('pid' => $id);
        $status = $db->delete('services', $aWhere, $limit)->affectedRows();


        if ($status == 1) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Service has been deleted successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }


    if ($act == 'add_product') {

        $product_name = $_POST['product_name'];
        $product_code = $_POST['product_code'];
        $product_price = $_POST['product_price'];
        $product_tax = $_POST['product_tax'];


        $dataArray = array('product_name' => $product_name, 'product_code' => $product_code, 'product_price' => $product_price, 'tax' => $product_tax);
        $status = false;

        $status = $db->insert('services', $dataArray)->rStatus();


        header('Content-Type: application/json');


        if ($status == true) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Service has been added successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please check stock units.'));
        }


    }


} else {

    echo json_encode(array('status' => 'Error', 'message' =>
        'You are not authorized for this action. Action reverted!!'));
}

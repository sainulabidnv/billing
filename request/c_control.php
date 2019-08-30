<?php
/**
 * Express Auto Biller Manager
 * Copyright (c) Rajesh Dukiya. All Rights Reserved
 * ***********************************************************************
 *
 *  E-mail: support@skyresoft.com *
 *
 *  ************************************************************************
 *  * This software is furnished under a license and may be used and copied
 *  * only  for one client use. Using for multi-clients to is prohibited
 *  * You will liable be punished under Indian Law (IPC)
 *
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


    if ($act == 'catupdate_product') {


        $getID = $_POST['id'];
        $cat_name = $_POST['catname'];
        $cat_des = $_POST['catdes'];


        $dataArray = array('title' => $cat_name, 'extra' => $cat_des);
        $aWhere = array('id' => $getID);
        $status = $db->update('product_cat', $dataArray, $aWhere)->rStatus();


        if ($status == true) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Product category has been updated successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error, please try again.'));
        }


    }


    if ($act == 'addcat_product') {
        $product_cat = $_POST['catname'];
        $product_name = $_POST['catdes'];


        $dataArray = array('title' => $product_cat, 'extra' => $product_name);
        $status = false;

        $status = $db->insert('product_cat', $dataArray)->rStatus();


        header('Content-Type: application/json');


        if ($status == true) {

            echo json_encode(array('status' => 'Success', 'message' =>
                'Product category has been added successfully!'));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'There has been an error.'));
        }


    }


    if ($act == 'delete_catproduct') {


        $id = $_POST["delete"];


        $limit = 'LIMIT 1';
        $aWhere = array('id' => $id);
        $aWhere1 = array('pcat' => $id);
        if ($id > 1) {
            $status = $db->delete('products', $aWhere1)->affectedRows();
            $status = $db->delete('product_cat', $aWhere, $limit)->affectedRows();


            if ($status == 1) {

                echo json_encode(array('status' => 'Success', 'message' =>
                    'Product category has been deleted successfully!'));

            } else {

                echo json_encode(array('status' => 'Error', 'message' =>
                    'There has been an error, please try again.'));
            }
        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                'You can not delete the General category, at least one general category required in product manager'));
        }


    }


} else {

    echo json_encode(array('status' => 'Error', 'message' =>
        'You are not authorized for this action. Action reverted!!'));
}

<style type="text/css">
	body { margin-top:15px;}
	div { float:left; width:30%; margin:0 1% 30px;}
	svg { width:100%}
</style>
<?php
/**
 * Express Invoice & Stock Manager
 * Copyright (c) Rajesh Dukiya. All Rights Reserved
 * ***********************************************************************
 *
 *  Email: support@skyresoft.com
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

/*
error reporting 
to set production environment please use 
error_reporting(0);
*/
error_reporting(E_ALL);

//installation checking
if (file_exists("includes/config.php")) {
    require_once("includes/config.php");
} else {
    
    exit;
}
// include pdo class wrapper
include_once 'lib/pdowrapper/class.pdowrapper.php';
include_once('lib/barcode/barcode.php');
// database connection setings
$dbConfig = array("host" => $dbhost, "dbname" => $dbname, "username" => $dbuser, "password" => $dbpass);
// get instance of PDO Wrapper object
$db = new PdoWrapper($dbConfig);
//site configration loading and login check
include('includes/globalcf.php');
include('includes/system.php');

function barcode($id){ 
$barcode = new barcode_generator();
return $barcode->render_svg('code-39', $id, '');
}

//timezone settings
date_default_timezone_set($siteConfig['zone']);
//$user->login('admin','admin');
//get page and content

//echo '<pre>';
//print_r($_POST);

 //if (!$user->isSigned()) {  redirect(SITE);  }
 
 if(isset($_POST['bcnt'])) {
	 $i = 0;
	 foreach($_POST['bcnt'] as $cnt) {
		 if($cnt!="") { 
		 for($j=0; $j<$cnt; $j++) { echo "<div>".barcode($_POST["bname"][$i])."</div>"; }
		 
		 }
		 
		 $i++;
		 }
	 
	 }

 
 
 
 exit;
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 

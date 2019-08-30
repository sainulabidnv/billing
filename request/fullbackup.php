<?php
include_once('../includes/config.php');
include_once('../includes/system.php');
include_once('../lib/pdowrapper/class.pdowrapper.php');
include_once('../includes/mysqldump.php');
use Ifsnop\Mysqldump as IMysqldump;

global $user;
if ( $user->group_id == 1) {
	
$dirName 	= "../dbbackup/";
array_map('unlink', glob($dirName."/*"));
$dump 		= new IMysqldump\Mysqldump(  "mysql:host=$dbhost;dbname=$dbname", $dbuser,  $dbpass );
$Name 		= "db_".date("d-m-Y");
$fName 		= md5(date("d-m-Y"));

$dump->start($dirName.$fName);

//Downloading...
$file_url = $dirName.$fName;
header('Content-Type: application/text');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
header('Content-Disposition: filename='.$Name);
readfile($file_url); // do the double-download-dance (dirty but worky)



exit(0);

	
	}


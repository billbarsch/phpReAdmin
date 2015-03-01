<?php
// Load the driver
require_once("rdb/rdb.php");
try{
	// Connect to localhost
	if($_SESSION["host_address"]!='')
	@ $conn = r\connect($_SESSION["host_address"]);
} catch (Exception $e){ 
	if(!isset($conn)){
		$_SESSION["host_address"]='';
		//die("error");
	}
}
?>
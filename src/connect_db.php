<?php
// Load the driver
require_once("rdb2/rdb.php");
try{
	// Connect to host
	if(!empty($_SESSION["host_address"]))
		@ $conn = r\connect($_SESSION["host_address"]);
} catch (Exception $e){ 
	if(empty($conn)){
		$_SESSION["host_address"]='';
		//die("error");
	}
}
?>
<?php
//rethinkdb host ip adress
if(empty($_SESSION["host_address"])){
	$_SESSION["host_address"] = '';
	//$_SESSION["host_address"] = 'localhost';
	//$_SESSION["host_address"] = '192.168.0.10';
}
?>
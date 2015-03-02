<?php
header('Content-Type: text/html; charset=utf-8');

//rethinkdb host ip adress
if(!isset($_SESSION["host_address"])){
	$_SESSION["host_address"] = '';
	//$_SESSION["host_address"] = 'localhost';
	//$_SESSION["host_address"] = '192.168.0.10';
}
?>
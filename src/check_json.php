<?php
$result = array("valid_json"=>true);
if(!json_decode($_REQUEST["json_text"])){
	$result = array("valid_json"=>false);
}
echo json_encode($result);
?>
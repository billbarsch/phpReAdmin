<?php
if(isset($_REQUEST["action"]))
if(($_REQUEST["action"]=="update")){
	$json_string = $_REQUEST["json_text"];
	$json_array = json_decode($json_string);
	//	unset($json_array["id"]);
	$update = r\db($_REQUEST["database"])->table($_REQUEST["table"])->get($_REQUEST["doc"])->replace($json_array)->run($conn);
	$update = $update->toNative();
	if($update["replaced"]>0){
		?>
		<p class="bg-success">Doc updated successfully!</p>
		<?php
    }else{
		?>
		<p class="bg-danger">Error occurred</p>	
		<?php
    }
}

if(isset($_REQUEST["action"]))
if($_REQUEST["action"]=="insert"){
	//$json_string = utf8_encode($_REQUEST["json_text"]); // before json_decode
	$json_string = $_REQUEST["json_text"];
	$json_array = json_decode($json_string);
//	unset($json_array["id"]);
	$insert = r\db($_REQUEST["database"])->table($_REQUEST["table"])->insert($json_array)->run($conn);
	$insert = $insert->toNative();
	if($insert["inserted"]>0){
		?>
		<p class="bg-success">Doc inserted successfully!</p>
		<?php
    }else{
		?>
		<p class="bg-danger">Error occurred</p>	
		<?php
    }
	$_REQUEST["doc"] = $insert["generated_keys"][0];
}

$action = "insert";
if(isset($_REQUEST["doc"]))
if($_REQUEST["doc"]!=="new")
	$action = "update";
?>
<h1 class="page-header">
<?php
if($action=="insert"){
	?>New Doc<?php
}else{
	?>Doc (<?php echo $_REQUEST["doc"];?>)<?php
}?>
</h1>
<!-- <h2 class="sub-header"></h2> -->
<?php
$json_text = "";
if($action=="update"){
	$doc = r\db($_REQUEST["database"])->table($_REQUEST["table"])->get($_REQUEST["doc"])->run($conn);
	$json_text = json_encode($doc->toNative(),JSON_UNESCAPED_UNICODE);
	$doc = $doc->toNative();
}
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&table=<?php echo $_REQUEST["table"];?>&doc=<?php 
if($action=="update")
	echo $doc["id"];
else
	echo "new";
?>&action=<?php echo $action;?>">
  <div class="form-group">
    <label for="json_text">json</label>
    <textarea rows="19" name="json_text" class="form-control" id="json_text" placeholder="{...}"><?php echo indent($json_text); ?></textarea>
  </div>
  <button type="submit" class="btn btn-default">Replace</button>
</form>
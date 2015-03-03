<?php
include("safereval/class.safereval.php");
include("safereval/config.safereval.php");

if(!isset($_REQUEST["query_text"]))
	$_REQUEST["query_text"] = "";
?>
<h1 class="page-header">
PHP Query:
</h1>
<!-- <h2 class="sub-header"></h2> -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?<?php if(isset($_REQUEST["database"])){ echo "database=".$_REQUEST["database"]."&"; }?>query=true&action=run">
<?php 
if((isset($_REQUEST["database"]))
and($_REQUEST["database"]!='')){ 
?>
  <div class="checkbox">
    <label>
 	<input type="checkbox" name="use_db" id="use_db" value="<?php echo $_REQUEST["database"];?>" <?php if(isset($_REQUEST["use_db"])) echo "checked";?>>
  	$conn->useDb('<?php echo $_REQUEST["database"];?>');
  	</label>
  </div>
<?php
}
?>
  <div class="form-group">
    <textarea rows="10" name="query_text" class="form-control" id="query_text" placeholder="$result = r\db('db_name')->table('table_name')->filter(array('key_name'=>'value'))->run($conn);"><?php echo $_REQUEST["query_text"];?></textarea>
  </div>
  <button type="submit" class="btn btn-default">Run!</button>
</form>

<?php
if(isset($_REQUEST["action"]))
if(($_REQUEST["action"]=="run")){
	$result = array();
	try {
		set_time_limit(15);
		$command = $_REQUEST["query_text"];
		$command = trim($command);
		if($command!==''){
			
			//use default user db connection 	
			if(isset($_REQUEST["use_db"]))
			$conn->useDb($_REQUEST["database"]);
				
			//#################EVAL###################	
			//I'm having problems using safereval 
			/*			
			$se = new SaferEval();
			$errors = $se->checkScript($command, 1);
			if ($errors) print_r($se->htmlErrors($errors));
 			*/

			//use EVAL function for your own risk
			eval($command);
			//########################################
			
			if(method_exists($result,"toNative")){
				$result = $result->toNative();
			}
			?>
			<pre><?php echo indent(json_encode($result));?></pre>
			<?php
		}
    } catch (Exception $e) {
		echo $e;
	}
}
?>
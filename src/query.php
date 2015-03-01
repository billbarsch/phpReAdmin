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
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?query=true&action=run">
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
		$command = $_REQUEST["query_text"];
		$command = trim($command);
		if($command!==''){
			$se = new SaferEval();
			$errors = $se->checkScript($command, 1);
			// Error output
			if ($errors) print_r($se->htmlErrors($errors));
			//else echo '<p>No errors.</p>';

			if(method_exists($result,"toNative")){
				$result = $result->toNative();
			}
			?>
			<pre><?php echo indent(json_encode($result));?></pre>
			<?php
		}
/*
		$commands = explode(";",$_REQUEST["query_text"]);
		foreach($commands as $command){
			$command = trim($command);
			if($command!==''){
				$command  = "\$result = " . $command . ";";
				$se = new SaferEval();
				$errors = $se->checkScript($command, 1);
				// Error output
				if ($errors) print_r($se->htmlErrors($errors));
				//else echo '<p>No errors.</p>';
				
				if(method_exists($result,"toNative")){
					$result = $result->toNative();
				}
				?>
				<pre><?php echo indent(json_encode($result));?></pre>
				<?php
			}
		}
*/

    } catch (Exception $e) {
		echo $e;
	}
}
?>
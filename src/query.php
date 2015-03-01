<?php
if(!isset($_REQUEST["query_text"]))
	$_REQUEST["query_text"] = "";
?>
<h1 class="page-header">
PHP Query:
</h1>
<!-- <h2 class="sub-header"></h2> -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?query=true&action=run">
  <div class="form-group">
    <label for="query_text">json</label>
    <textarea rows="10" name="query_text" class="form-control" id="query_text" placeholder="r\db('db_name')->table('table_name')->filter(array('key_name'=>'value'))->run($conn);"><?php echo $_REQUEST["query_text"];?></textarea>
  </div>
  <button type="submit" class="btn btn-default">Run!</button>
</form>

<?php
if(isset($_REQUEST["action"]))
if(($_REQUEST["action"]=="run")){
	$result = array();
	try {
		$_REQUEST["query_text"]  = "\$result = " . $_REQUEST["query_text"] ;
		eval($_REQUEST["query_text"]);
	?>
    <pre><?php echo indent(json_encode($result->toNative()));?></pre>
	<?php
    } catch (Exception $e) {
		echo $e;
	}
}
?>
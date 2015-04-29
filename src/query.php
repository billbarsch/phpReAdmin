<?php
include("safereval/class.safereval.php");
include("safereval/config.safereval.php");

if(!isset($_REQUEST["query_text"]))
	$_REQUEST["query_text"] = "";
?>
<script>
$(function(){
	$(document).on("dblclick",".query_history",function(){
		$("#query_text").val($(this).val());
		$("#history_modal").modal("hide");
	})
})	
</script>

<h1 class="page-header">
PHP Query:
</h1>
<div style="text-align: right;">
<a data-toggle="modal" data-target="#history_modal" class="btn btn-primary" role="button">query history</a>
</div>
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
function is_assoc($array) {
  foreach (array_keys($array) as $k => $v) {
    if ($k !== $v)
      return true;
  }
  return false;
}

if(isset($_REQUEST["action"]))
if(($_REQUEST["action"]=="run")){
	$result = array();
	try {
		set_time_limit(30);
		$command = $_REQUEST["query_text"];
		$command = trim($command);
		if($command!==''){
			
			//save query on memory
			$_SESSION["query_history"][] = $command;
			
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
			
			/*if(method_exists($result,"toNative")){
				$result = $result->toNative();
			}*/
			$resultArray = [];
			if(is_a($result,"ArrayObject")){
				$resultArray = $result;
			}else{
				foreach ($result as $value) {
					$resultArray[] = $value; 
				}
			}
			?>
			<pre><?php echo json_encode($resultArray,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);?></pre>
			<?php
		}
    } catch (Exception $e) {
		echo $e;
	}
}
?>

<!-- query history modal -->
        <div class="modal fade" id="history_modal" role="dialog" aria-labelledby="history_modalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="history_modalLabel">Query History</h4>
              </div>
              <div class="modal-body">
       			<ul class="nav nav-pills nav-stacked">
				<?php
				if(isset($_SESSION["query_history"])){
					$history=array_reverse($_SESSION["query_history"]);
					$history_index = 0;
					foreach($history as $query){
						$rows = "10";
						if($history_index>0)
							$rows = "6";
						if($history_index>6)
							$rows = "3";
						
						?>
						<li title="Double click to transfer" role="presentation"><textarea rows="<?php echo $rows;?>" class="query_history form-control"><?php echo $query;?></textarea></li>
						<?php
					$history_index++;
					}
				}
				?>
				</ul>	
              </div>
            </div>
          </div>
        </div>
<!-- query history modal -->

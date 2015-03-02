<?php
if(isset($_REQUEST["action"]))
if($_REQUEST["action"]=="rename_table"){
	$rename_table = r\db($_REQUEST["database"])->table($_REQUEST["old_table_name"])->config()->update(array("name"=>$_REQUEST["new_table_name"]))->run($conn);
	$rename_table = $rename_table->toNative();
	if($rename_table["replaced"]>0){
		?>
		<p class="bg-success">Table renamed successfully!</p>
		<?php
    }else{
		?>
		<p class="bg-danger">Error occurred</p>	
		<?php
    }
}

if(isset($_REQUEST["action"]))
if($_REQUEST["action"]=="new_table"){
	$new_table = r\db($_REQUEST["database"])->tableCreate($_REQUEST["table_name"])->run($conn);
	$new_table = $new_table->toNative();
	if($new_table["tables_created"]>0){
		?>
		<p class="bg-success">Table created successfully!</p>
		<?php
    }else{
		?>
		<p class="bg-danger">Error occurred</p>	
		<?php
    }
}

if(isset($_REQUEST["action"]))
if($_REQUEST["action"]=="drop_table"){
	$drop_table = r\db($_REQUEST["database"])->tableDrop($_REQUEST["table"])->run($conn);
	$drop_table = $drop_table->toNative();
	if($drop_table["tables_dropped"]>0){
		?>
		<p class="bg-success">Table dropped successfully!</p>
		<?php
    }else{
		?>
		<p class="bg-danger">Error occurred</p>	
		<?php
    }
}

?>
<script>
$(function(){
	$(document).on("click",".btn-rename",function(){
		$("#renameModal").find("#old_table_name").val($(this).attr("old_table_name"));
		$("#renameModal").find("#new_table_name").val($(this).attr("old_table_name"));
	});
});
</script>
<!-- create modal -->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="createModalLabel">Create table</h4>
              </div>
              <div class="modal-body">
                  <form id="InfroText" action="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&table=new&action=new_table" method="POST">
                      <div class="form-group">
                          <label for="table_name">Table name</label>
                          <input type="text" name="table_name" id="table_name" required>
                      </div>
                  <div style="text-align:right">    
                  <button type="submit" class="btn btn-primary">create table</button>
                  </div>
                  </form>
              </div>
            </div>
          </div>
        </div>
<!-- create modal -->


<!-- rename modal -->
        <div class="modal fade" id="renameModal" tabindex="-1" role="dialog" aria-labelledby="renameModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="renameModalLabel">Rename table</h4>
              </div>
              <div class="modal-body">
                  <form id="InfroText" action="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&action=rename_table" method="POST">
                      <div class="form-group">
                          <label for="table_name">New table name</label>
                          <input type="hidden" name="old_table_name" id="old_table_name" value="">
                          <input type="text" name="new_table_name" id="new_table_name" required>
                      </div>
                  <div style="text-align:right">    
                  <button type="submit" class="btn btn-primary">rename table</button>
                  </div>
                  </form>
              </div>
            </div>
          </div>
        </div>
<!-- rename modal -->

         <h1 class="page-header"><?php echo $_REQUEST["database"];?>/Tables</h1>
          <!-- <h2 class="sub-header"></h2> -->
         <a data-toggle="modal" data-target="#createModal" class="btn btn-primary" role="button">+ new table</a>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
				<?php
                $tables = r\db($_REQUEST["database"])->tableList()->run($conn);
                $tables = $tables->toNative();
                foreach($tables as $table){
                ?>
                <tr>
                  <td><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&table=<?php echo $table;?>"><?php echo $table;?></a></td>
                  <td><a data-toggle="modal" data-target="#renameModal" class="btn-rename btn btn-warning" role="button" old_table_name="<?php echo $table; ?>">rename</a></td>
                  <td><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&table=<?php echo $table;?>&action=drop_table" class="btn btn-danger" role="button">drop</a></td>
                </tr>
                <?php
                }//each tables
                ?>
              </tbody>
            </table>
          </div>
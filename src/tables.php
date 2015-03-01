<?php
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

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Create table</h4>
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

         <h1 class="page-header"><?php echo $_REQUEST["database"];?>/Tables</h1>
          <!-- <h2 class="sub-header"></h2> -->
         <a data-toggle="modal" data-target="#myModal" class="btn btn-primary" role="button">+ new table</a>
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
                  <td><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&table=<?php echo $table;?>&action=drop_table" class="btn btn-danger" role="button">drop</a></td>
                  <td></td>
                </tr>
                <?php
                }//each tables
                ?>

              </tbody>
            </table>
          </div>
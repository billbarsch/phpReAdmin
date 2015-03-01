<?php
if(isset($_REQUEST["action"]))
if($_REQUEST["action"]=="new_database"){
	$new_database = r\dbCreate($_REQUEST["database_name"])->run($conn);
	$new_database = $new_database->toNative();
	if($new_database["dbs_created"]>0){
		?>
		<p class="bg-success">Database created successfully!</p>
		<?php
    }else{
		?>
		<p class="bg-danger">Error occurred</p>	
		<?php
    }
}

if(isset($_REQUEST["action"]))
if($_REQUEST["action"]=="drop_database"){
	$drop_database = r\dbDrop($_REQUEST["database"])->run($conn);
	$drop_database = $drop_database->toNative();
	if($drop_database["dbs_dropped"]>0){
		?>
		<p class="bg-success">Database dropped successfully!</p>
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
                  <form id="InfroText" action="<?php echo $_SERVER['PHP_SELF'];?>?database=new&action=new_database" method="POST">
                      <div class="form-group">
                          <label for="database_name">Database name</label>
                          <input type="text" name="database_name" id="database_name" required>
                      </div>
                  <div style="text-align:right">    
                  <button type="submit" class="btn btn-primary">create database</button>
                  </div>
                  </form>
              </div>
            </div>
          </div>
        </div>

         <h1 class="page-header">Databases</h1>
          <!-- <h2 class="sub-header"></h2> -->
         <a data-toggle="modal" data-target="#myModal" class="btn btn-primary" role="button">+ new database</a>
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
                $databases = r\dbList()->run($conn);
                $databases = $databases->toNative();
                foreach($databases as $database){
                ?>
                <tr>
                  <td><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $database;?>"><?php echo $database;?></a></td>
                  <td><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $database;?>&action=drop_database" class="btn btn-danger" role="button">drop</a></td>
                  <td></td>
                </tr>
                <?php
                }//each databases
                ?>

              </tbody>
            </table>
          </div>
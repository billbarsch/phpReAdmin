<?php
if(isset($_REQUEST["action"]))
if($_REQUEST["action"]=="delete_doc"){
	$delete = r\db($_REQUEST["database"])->table($_REQUEST["table"])->get($_REQUEST["doc"])->delete()->run($conn);
	$delete = $delete->toNative();
	if($delete["deleted"]>0){
		?>
		<p class="bg-success">Doc deleted successfully!</p>
		<?php
    }else{
		?>
		<p class="bg-danger">Error occurred</p>	
		<?php
    }
}
?>
         <h1 class="page-header">Docs (<?php echo $_REQUEST["table"];?>)</h1>
          <!-- <h2 class="sub-header"></h2> -->
          <a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&table=<?php echo $_REQUEST["table"];?>&doc=new" class="btn btn-primary" role="button">+ new doc</a>
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
				
                $docs = r\db($_REQUEST["database"])->table($_REQUEST["table"])->run($conn);
				$docs = $docs->toNative();
                foreach($docs as $doc){
				?>
                <tr>
                  <td><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&table=<?php echo $_REQUEST["table"];?>&doc=<?php echo $doc["id"];?>"><?php echo substr(json_encode($doc),0,100);?></a></td>
                  <td><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&table=<?php echo $_REQUEST["table"];?>&doc=<?php echo $doc["id"];?>&action=delete_doc" class="btn btn-danger" role="button">delete</a></td>
                  <td></td>
                </tr>
                <?php
                }//each databases
                ?>

              </tbody>
            </table>
          </div>
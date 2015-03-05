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
         <h1 class="page-header"><?php echo $_REQUEST["database"];?>/<?php echo $_REQUEST["table"];?>/Docs</h1>
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
				function first_keys($array){
					$result = array();
					foreach(array_keys($array) as $key){
						if(is_array($array[$key])||is_object($array[$key])){
							$result[$key] = "{..}";
						}else{
							$result[$key] = $array[$key];
						}
					}
					return $result;
				}//first keys
                $docs = r\db($_REQUEST["database"])->table($_REQUEST["table"])->run($conn);
				$docs = $docs->toNative();
                foreach($docs as $doc){
					$doc = first_keys($doc);
                	$doc_text = json_encode($doc,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
					$doc_text = substr(str_replace('"{..}"','{..}',$doc_text),0,100);
					?>
	                <tr>
	                  <td><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&table=<?php echo $_REQUEST["table"];?>&doc=<?php echo $doc["id"];?>"><?php echo $doc_text;?></a></td>
	                  <td><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&table=<?php echo $_REQUEST["table"];?>&doc=<?php echo $doc["id"];?>&action=delete_doc" class="btn btn-danger" role="button">delete</a></td>
	                  <td></td>
	                </tr>
	                <?php
                }//each doc
                ?>

              </tbody>
            </table>
          </div>
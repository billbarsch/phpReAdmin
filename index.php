<?php
header('Content-Type: text/html; charset=utf-8');
session_start();

if(isset($_REQUEST["action"]))
	if($_REQUEST["action"]=="config"){
		$_SESSION["host_address"] = $_REQUEST["host_address"];
	}

if(isset($_REQUEST["action"]))
	if($_REQUEST["action"]=="disconnect"){
		$_SESSION["host_address"] = '';
	}

include "config.php";
include "src/connect_db.php";

if(!isset($_REQUEST["database"]))
	$_REQUEST["database"] = '';
if(!isset($_REQUEST["table"]))
	$_REQUEST["table"] = '';
if(!isset($_REQUEST["doc"]))
	$_REQUEST["doc"] = '';
if(!isset($_REQUEST["action"]))
	$_REQUEST["action"] = "";
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>phpReAdmin</title>
    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="bootstrap/dashboard.css" rel="stylesheet">
    <script src="bootstrap/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </head>

  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="<?php echo $_SERVER['PHP_SELF'];?>">phpReAdmin (0.3)</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-left">
            <li><a href="<?php echo $_SERVER['PHP_SELF'];?>">Databases</a></li>
            <li class="<?php if($_REQUEST["database"]=='') echo "disabled";?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>">Tables</a></li>
            <li class="<?php if($_REQUEST["table"]=='') echo "disabled";?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&table=<?php echo $_REQUEST["table"];?>">Docs</a></li>
            <?php 
            if((isset($_REQUEST["database"]))
			and($_REQUEST["database"]!='')){ 
				?>
	            <li><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&query=true">Query</a></li>			
	            <?php 
			}else{
				?>
	            <li><a href="<?php echo $_SERVER['PHP_SELF'];?>?query=true">Query</a></li>
				<?php
			}
			?>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo $_SERVER['PHP_SELF'];?>?action=disconnect">Exit</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container-fluid">
      <div class="row">
<?php
if($_SESSION["host_address"]!==''){   
?>   
        <div class="col-sm-3 col-md-2 sidebar">
            <div class="panel-group" role="tablist">
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="collapseListGroupHeading1">
                    <h4 class="panel-title">
                      <a class="collapsed" data-toggle="collapse" href="#collapseListGroup1" aria-expanded="false" aria-controls="collapseListGroup1">
                        Databases
                      </a>
                    </h4>
                  </div>
                  <div id="collapseListGroup1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="collapseListGroupHeading1">
                    <ul class="list-group">
                    <?php
					$databases = r\dbList()->run($conn);
					//$databases = $databases->toNative();
					foreach($databases as $database){
					?>
                    	<li class="list-group-item"><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $database;?>"><?php echo $database;?></a></li>
                    <?php
					}//each databases
					?>
                    </ul>
                    <div class="panel-footer"></div>
                  </div>
                </div>
              </div>
        </div>
<?php
}
?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
 		<?php
		$to_include = "databases.php";
		
		if($_REQUEST["database"]!==''){
			if(($_REQUEST["action"]=="drop_database")
			or($_REQUEST["action"]=="new_database")
			or($_REQUEST["action"]=="config")
			or($_REQUEST["action"]=="disconnect")){
				$to_include = "databases.php";
			}else{
				$to_include = "tables.php";
			}	
		}
		
		if($_REQUEST["table"]!==''){
			if(($_REQUEST["action"]=="drop_table")
			or($_REQUEST["action"]=="new_table")
			or($_REQUEST["action"]=="rename_table")){
				$to_include = "tables.php";
			}else{
				$to_include = "docs.php";
			}
		}

		if($_REQUEST["doc"]!==""){
			if($_REQUEST["action"]=="delete_doc"){
				$to_include = "docs.php";
			}else{
				$to_include = "doc.php";
			}
			
		}
		
		if(isset($_REQUEST["query"]))
			$to_include = "query.php";
		
		include("src/".$to_include);
		?>
        </div>
      </div>
    </div>
  </body>
</html>

<?php
include "config.php";
include "src/connect_db.php";


/********************utils******************/
function indent($json) {

    $result      = '';
    $pos         = 0;
    $strLen      = strlen($json);
    $indentStr   = '  ';
    $newLine     = "\n";
    $prevChar    = '';
    $outOfQuotes = true;

    for ($i=0; $i<=$strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;

        // If this character is the end of an element,
        // output a new line and indent the next line.
        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $result .= $indentStr;
            }
        }

        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element,
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        $prevChar = $char;
    }

    return $result;
}
//******************utils***********************



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
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>phpReAdmin</title>
    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="bootstrap/dashboard.css" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="<?php echo $_SERVER['PHP_SELF'];?>">phpReAdmin (beta)</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo $_SERVER['PHP_SELF'];?>">Databases</a></li>
            <li class="<?php if($_REQUEST["database"]=='') echo "disabled";?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>">Tables</a></li>
            <li class="<?php if($_REQUEST["table"]=='') echo "disabled";?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?database=<?php echo $_REQUEST["database"];?>&table=<?php echo $_REQUEST["table"];?>">Docs</a></li>
            <li><a href="<?php echo $_SERVER['PHP_SELF'];?>?query=true">Query</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
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
					$databases = $databases->toNative();
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
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
 		<?php
		$to_include = "databases.php";
		
		if(!$_REQUEST["database"]==''){
			if(($_REQUEST["action"]=="drop_database")
			or($_REQUEST["action"]=="new_database")){
				$to_include = "databases.php";
			}else{
				$to_include = "tables.php";
			}	
		}
		
		if(!$_REQUEST["table"]==''){
			if(($_REQUEST["action"]=="drop_table")
			or($_REQUEST["action"]=="new_table")){
				$to_include = "tables.php";
			}else{
				$to_include = "docs.php";
			}
		}

		if(!$_REQUEST["doc"]==''){
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

    <script src="bootstrap/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>

<?php
include 'id_verify.php'; //$user included as S# from cookie
include 'connect.php';



$id = $_SESSION['eventID']; //set event ID - if posting different change
if(!empty($_POST['eventID'])){
$_SESSION['eventID'] = $_POST['eventID']; $id = $_POST['eventID'];
}

$SNum = $_POST['SNum'];
$searchSNum = $_POST['searchSNum'];
$nameSearch = $_POST['nameSearch'];
$formSubmit = $_POST['formSubmit'];
$SNumImport = $_POST['SNumImport'];
$proposeSubmit = $_POST['proposeSubmit'];
$proposeCancel = $_POST['proposeCancel'];

if(empty($_SESSION['namesAdd'])){$_SESSION["namesAdd"] = array();}


//Display pending names function
function displayName($SNumVal) {
	global $con;
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT DISTINCT LastName, FirstName FROM TeachingInfo WHERE SNum = '$SNumVal'"));
	$LastName = $getID["LastName"];
	$FirstName = $getID["FirstName"];
	$name = $LastName . ", " . $FirstName;
	echo $name;
}

//Close Display pending names function

//Propose Cancel
if($proposeCancel == "true") {
	$_SESSION["namesAdd"] = array();
}
//Close Propose Cancel

//Delete Name
if($_POST['deleteName'] == "true") {
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM Events WHERE id = '$id'"));
	$Regs = $getID["Regs"]; // find S#s of database saved registrants
	$SNumDelete = $_POST['SNumDelete'] . " "; //get S# plus space to be removed from panel form button
	$newRegs = str_replace($SNumDelete, "", $Regs, $count); //remove S# from string
	
	if($count == 0){ // if replace count is zero, means that it is last record (without trailing space)
		$SNumDelete = " " . $_POST['SNumDelete']; //add preceding space
		$newRegs = str_replace($SNumDelete, "", $Regs, $count); // then remove
		}
		
	mysqli_query($con,"UPDATE Events SET Regs = '$newRegs' WHERE id = '$id'");
}
//Close Delete Name

// SQL Send
if($proposeSubmit == "true") {
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM Events WHERE id = '$id'"));
	$Regs = $getID["Regs"];
	$arraySplit = implode(" ", $_SESSION["namesAdd"]); //implode saved S#s into string

		if($Regs == NULL) {		
			mysqli_query($con,"UPDATE Events SET Regs = '$arraySplit' WHERE id = '$id'");
			$_SESSION["namesAdd"] = array(); //reset saved S#s
		}
		else {
			$new = $Regs . " " . $arraySplit; //add to end of existing database S#s
			mysqli_query($con,"UPDATE Events SET Regs = '$new' WHERE id = '$id'");
			$_SESSION["namesAdd"] = array(); //reset saved S#s
		}

}
// Close SQL Send

if(!empty($searchSNum)){

//$result = mysqli_query($con,"SELECT DISTINCT LastName, FirstName FROM TeachingInfo WHERE SNum = '$SNum'");

	// retrieving single MySQL record. valu = Column name
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT DISTINCT LastName, FirstName FROM TeachingInfo WHERE SNum = '$SNum'"));
	$LastName = $getID["LastName"];
	$FirstName = $getID["FirstName"];
	$SNum = $searchSNum;
	//array_push($_SESSION["namesAdd"],$SNum);
	
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM Events WHERE id = '$id'"));
	$Regs = $getID["Regs"];
	//$arraySplit = implode(" ", $_SESSION["namesAdd"]); //implode saved S#s into string

		if($Regs == NULL) {		
			mysqli_query($con,"UPDATE Events SET Regs = '$SNum' WHERE id = '$id'");
			//$_SESSION["namesAdd"] = array(); //reset saved S#s
		}
		else {
			$new = $Regs . " " . $SNum; //add to end of existing database S#s
			mysqli_query($con,"UPDATE Events SET Regs = '$new' WHERE id = '$id'");
			//$_SESSION["namesAdd"] = array(); //reset saved S#s
		}
	
}

if(!empty($nameSearch)){
	$splitFirst = explode(", ", $nameSearch);
	$LastName = $splitFirst[0];
	$FirstName = $splitFirst[1];
	
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT DISTINCT SNum FROM TeachingInfo WHERE LastName = '$LastName' AND FirstName = '$FirstName'"));
	$SNum = $getID["SNum"];
	//array_push($_SESSION["namesAdd"],$SNum);
	
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM Events WHERE id = '$id'"));
	$Regs = $getID["Regs"];
	//$arraySplit = implode(" ", $_SESSION["namesAdd"]); //implode saved S#s into string

		if($Regs == NULL) {		
			mysqli_query($con,"UPDATE Events SET Regs = '$SNum' WHERE id = '$id'");
			//$_SESSION["namesAdd"] = array(); //reset saved S#s
		}
		else {
			$new = $Regs . " " . $SNum; //add to end of existing database S#s
			mysqli_query($con,"UPDATE Events SET Regs = '$new' WHERE id = '$id'");
			//$_SESSION["namesAdd"] = array(); //reset saved S#s
		}
}

// ************** Find a way to display comments!!!!!!!!!********************************
$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM Events WHERE id = '$id'"));
$Type = $getID["Type"];
$Item = $getID["Item"];
$Date = $getID["Date"];
$Sponsor = $getID["Sponsor"];
$Hours = $getID["Hours"];
$Complete = $getID["Complete"];

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Edit Event</title>
	


	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="js/tether.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	

	<!-- Demo stuff -->
	<link rel="stylesheet" href="css/jq.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link href="css/prettify.css" rel="stylesheet">
	<script src="js/prettify.js"></script>
	<script src="js/docs.js"></script>

	<!-- Tablesorter: required for bootstrap -->
	<link rel="stylesheet" href="css/theme.bootstrap.css">
	<script src="js/jquery.tablesorter.js"></script>
	<script src="js/jquery.tablesorter.widgets.js"></script>

	<!-- Tablesorter: optional -->
	<link rel="stylesheet" href="addons/pager/jquery.tablesorter.pager.css">
	<script src="addons/pager/jquery.tablesorter.pager.js"></script>



<!-- jQuery UI autocomplete -->

	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	
	<script>
	$(function() {
	    $( "#nameSearch" ).autocomplete({
	        source: 'searchNew.php',
	        minLength: 4,
	    });
	});
	</script>
	

<!-- /jQuery UI autocomplete -->

<!--Tooltips-->
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
<!--/Tooltips-->

</head>
<body role="document">

    <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Home</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="events.php">Events</a></li>
            <li><a href="#about">Credentials</a></li>
            <li><a href="#contact">PlaceHolder</a></li>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">Add<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="add_dev.php">Add ProfDev</a></li>
                <li><a href="add_event.php">Add Event</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

<!--
<div id="banner">
	<h1>table<em>sorter</em></h1>
	<h2>jQuery UITheme Widget (Bootstrap v3.x)</h2>
	<h3>Flexible client-side table sorting</h3>
	<a href="index.html">Back to documentation</a>
</div>
-->
<div id="main">
<br><br>
<h1>Edit Event - <?php echo "$Type - $Item  - $Date  - $Sponsor"; ?> </h1>


<div class="container">
<div class="row">


<div class="col-md-3"><form method="post" action="edit_event.php">
        <div class="input-group">
            <input type="text" class="form-control ui-widget" placeholder="Search LastName" name="nameSearch" id="nameSearch">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>        
     </form></div>
     
 <div class="col-md-3"><form method="post" action="edit_event.php">
     <div class="input-group">
         <input type="text" class="form-control ui-widget" placeholder="Search SNum" name="searchSNum" id="searchSNum">
         <div class="input-group-btn">
             <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
         </div>
     </div>    
  </form></div>
  
   <div class="col-md-3"><form method="post" action="edit_event.php">
     <div class="input-group">
         <textarea class="form-control" rows="3" name="SNumImport">Import list of S#s </textarea>
         <div class="input-group-btn">
             <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
         </div>
     </div>     
  </form></div>


	<div class="col-md-3">
		<?php
		if($Complete == NULL){
		
		echo '<form method="post" action="events.php">';
		echo '<input type="hidden" name="completeEvent" type="text" value="true">';
		echo '<input type="hidden" name="id" type="text" value="'.$id.'">'; 
		echo '<button type="submit" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="No further addition of attendees">Complete/Close Event</button></form>';
		}
		?>
	</div>

</div>
</div>


<div class="container">

  <div class="page-header">
        <h1>Attending</h1>
      </div>
      <div class="row">

        <!--transfer button-->
        <div class="col-sm-2"> <!--transfer button proposed to attending OR "X" if event is already complete-->
        <?php  
			if($Complete == NULL){
			echo  '<span class="glyphicon glyphicon-arrow-right" style="cursor:pointer; font-size:6em; display: block; text-align:center; color:blue;" 
			onclick="document.getElementById(\'submitForm\').submit();"></span>';
			}
			else{
			echo  '<span class="glyphicon glyphicon-remove" style="font-size:6em; display: block; text-align:center; color:red;"></span>';
			}
        
        ?>       
    
        </div>
        
        <div class="col-sm-10">  <!--Attending Panel Start-->
          <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title">Attending</h3>
            </div>
            <div class="panel-body">
                            
					<ul class="list-group">
						<?php 
						$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM Events WHERE id = '$id'"));
						$Regs = $getID["Regs"];
						$arrayJoin = explode(" ", $Regs); //explode saved S#s into array     
						foreach ($arrayJoin as $SNumX) {
							echo '<li class="list-group-item"><form method="post" action="edit_event.php"><div class="input-group">'; displayName($SNumX); 
								echo '<input type="hidden" name="deleteName" type="text" value="true">
								<input type="hidden" name="SNumDelete" type="text" value="'.$SNumX.'">
								<div class="input-group-btn"> <button  class="btn btn-default" type="submit"><i class="glyphicon glyphicon-minus" style="font-size:1em; color:red;"></i></button></div>
								</div></form>';					
						
							echo '</li>';
						}
						
						?>
					</ul>         
              
      
              
          </div>

        </div>

      </div>

</div>






</div>
<?php echo "$SNumImport"; ?>
</body>
</html>



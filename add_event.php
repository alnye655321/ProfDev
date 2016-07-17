<?php
include 'id_verify.php'; //$user included as S# from cookie
include '../connect.php';
$SNum = "S02064117";
$formSubmit = $_POST['formSubmit'];

$today = date("m/d/Y");

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add Event</title>
	


	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.min.js"></script>
	

	<!-- Demo stuff -->
	<link rel="stylesheet" href="css/jq.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link href="css/bootstrap-datepicker.min.css" rel="stylesheet" media="screen">
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


<!--Initialize Datepicker-->
<script type="text/javascript">
            // When the document is ready
            $(document).ready(function () {
              	
                
                $('#Date').datepicker({
                    format: "mm/dd/yyyy"
                    
                    
                });  
            
            });          
         
</script>
<!-- Close Initialize Datepicker-->


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
            <li class="active"><a href="events.php">Events</a></li>
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
<h1>Add Event</h1>



<div class="container">
<?php

// SQL Add
if($formSubmit == "true"){

$Type = $_POST['Type'];
$Other = $_POST['Other'];
$Item = $_POST['Item'];
$var = $_POST['Date'];
$Date = date("Y-m-d", strtotime($var));
$Sponsor = $_POST['Sponsor'];
$Hours = $_POST['Hours'];
$Comments = $_POST['Comments'];

if(!empty($Other)){$Type = $_POST['Other'];} // check if other is empty for type

$Comments=mysqli_real_escape_string($con3, $Comments);
$Comments = str_replace(",",";",$Comments);

$result = mysqli_query($con3,"SELECT id FROM Events ORDER BY id DESC LIMIT 1");
$row = mysqli_fetch_array($result);
$id=$row['id'] + 1;

$sql2 = "INSERT INTO Events (id, SNum, Type, Item, Date, Sponsor, Hours, Comments)
VALUES ('$id','$SNum','$Type','$Item','$Date','$Sponsor','$Hours','$Comments')";

if (mysqli_query($con3, $sql2)){

	echo "New record created successfully";}
	else {
    echo "Error: " . $sql2 . "<br>" . mysqli_error($con3);
}

}

// Form
else {

echo '<form role="form" method="post" action="add_event.php">';
echo '<div class="form-group">';
echo '<label for="Type">Type:</label>';
echo '<select class="form-control" id="Type" name="Type"';
echo 'onchange="';
echo "if(this.value=='Other')
{document.getElementById('lab1').style.display = 'inherit';}
 else {
 	document.getElementById('lab1').style.display = 'none';};";
echo '">"'; 
echo '<option>Activity</option>';
echo '<option>D2L</option>';
echo '<option>MPT</option>';
echo '<option>NFO</option>';
echo '<option>Project</option>';
echo '<option>Other</option>';
echo '</select></div>';

echo '<div class="form-group" id="lab1" style="display:none;">';
echo '<label for="Other">Other:</label>';
echo '<input type="text" class="form-control" name="Other" id="Other">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="Item">Item:</label>';
echo '<input type="text" class="form-control" name="Item" id="Item">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="Date">Date:</label>';
echo '<input type="text" class="form-control" name="Date" id="Date">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="Sponsor">Sponsor:</label>';
echo '<input type="text" class="form-control" name="Sponsor" id="Sponsor">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="Hours">Hours:</label>';
echo '<input type="text" class="form-control" name="Hours" id="Hours">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="Comments">Comments:</label>';
echo '<input type="text" class="form-control" name="Comments" id="Comments">';
echo '</div>';

echo '<input type="hidden" name="formSubmit" type="text" value="true">';

echo '<button type="submit" class="btn btn-default">Submit</button>';
echo '</form>';

}



?>
</div>
</div>

</body>
</html>



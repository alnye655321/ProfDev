<?php
include 'connect.php';
$SNum = $_POST['searchSNum'];
$nameSearch = $_POST['nameSearch'];
$formSubmit = $_POST['formSubmit'];

if(!empty($SNum)){

//$result = mysqli_query($con,"SELECT DISTINCT LastName, FirstName FROM TeachingInfo WHERE SNum = '$SNum'");

	// retrieving single MySQL record. valu = Column name
$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT DISTINCT LastName, FirstName FROM TeachingInfo WHERE SNum = '$SNum'"));
$LastName = $getID["LastName"];
$FirstName = $getID["FirstName"];

}

if(!empty($nameSearch)){
	$splitFirst = explode(", ", $nameSearch);
	$LastName = $splitFirst[0];
	$FirstName = $splitFirst[1];
	
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT DISTINCT SNum FROM TeachingInfo WHERE LastName = '$LastName' AND FirstName = '$FirstName'"));
	$SNum = $getID["SNum"];
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add Prof Dev</title>
	


	<!-- jQuery -->
	<script src="js/jquery-1.4.4.min.js"></script>
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
<h1>ProfDev Add</h1>


<div class="container">
<div class="row">


<div class="col-md-3"><form method="post" action="add_dev.php">
        <div class="input-group">
            <input type="text" class="form-control ui-widget" placeholder="Search LastName" name="nameSearch" id="nameSearch">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
     </form></div>
     
 <div class="col-md-3"><form method="post" action="add_dev.php">
     <div class="input-group">
         <input type="text" class="form-control ui-widget" placeholder="Search SNum" name="searchSNum" id="searchSNum">
         <div class="input-group-btn">
             <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
         </div>
     </div>
  </form></div>


</div>
</div>
<div class="container">
<?php

// SQL Add
if($formSubmit == "true"){

$SNum = $_POST['SNum'];
$LastName = $_POST['LastName'];
$FirstName = $_POST['FirstName'];
$Type = $_POST['Type'];
$Other = $_POST['Other'];
$Item = $_POST['Item'];
$Date = $_POST['Date'];
$Sponsor = $_POST['Sponsor'];
$Hours = $_POST['Hours'];
$Comments = $_POST['Comments'];

if(!empty($Other)){$Type = $_POST['Other'];} // check if other is empty for type

$Comments=mysqli_real_escape_string($con, $Comments);
$Comments = str_replace(",",";",$Comments);

$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT DISTINCT Subject FROM TeachingInfo WHERE SNum = '$SNum' LIMIT 1"));
$Prefix = $getID["Subject"];

$result = mysqli_query($con,"SELECT id FROM Activity ORDER BY id DESC LIMIT 1");
$row = mysqli_fetch_array($result);
$id=$row['id'] + 1;

$sql2 = "INSERT INTO Activity (id, SNum, LastName, FirstName, Type, Item, Date, Sponsor, Hours, Prefix, Comments)
VALUES ('$id','$SNum','$LastName','$FirstName','$Type','$Item','$Date','$Sponsor','$Hours','$Prefix','$Comments')";

if (mysqli_query($con, $sql2)){

	echo "New record created successfully";}
	else {
    echo "Error: " . $sql2 . "<br>" . mysqli_error($con);
}

}

// Form
else if(!empty($SNum) || !empty($nameSearch)){

echo '<form role="form" method="post" action="add_dev.php">';
echo '<div class="form-group">';
echo '<label for="SNum">SNum:</label>';
echo '<input type="text" class="form-control" name="SNum" id="SNum" value="'.$SNum.'" >';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="LastName">LastName:</label>';
echo '<input type="text" class="form-control" name="LastName" id="LastName" value="'.$LastName.'">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="FirstName">FirstName:</label>';
echo '<input type="text" class="form-control" name="FirstName" id="FirstName" value="'.$FirstName.'">';
echo '</div>';

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



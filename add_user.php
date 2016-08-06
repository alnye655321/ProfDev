<?php
include 'id_verify.php'; //$user included as S# from cookie
include 'connect.php';

$formSubmit = $_POST['formSubmit'];

//$today = date("m/d/Y");

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add New User</title>
	


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
<h1>Add New User</h1>



<div class="container">
<?php

// SQL Add
if($formSubmit == "true"){

$SNum = $_POST['SNum'];
$FirstName = $_POST['FirstName'];
$LastName = $_POST['LastName'];
$Prefix = $_POST['Prefix'];

if(!empty($Other)){$Type = $_POST['Other'];} // check if other is empty for type

$SNum=mysqli_real_escape_string($con, $SNum);
$SNum = str_replace(",",";",$SNum);

$FirstName=mysqli_real_escape_string($con, $FirstName);
$FirstName = str_replace(",",";",$FirstName);

$LastName=mysqli_real_escape_string($con, $LastName);
$LastName = str_replace(",",";",$LastName);

$result = mysqli_query($con,"SELECT CRN FROM TeachingInfo ORDER BY CRN DESC LIMIT 1"); //set very high CRN to distinguish from actual courses. On first run set above 10 000 000 
	$row = mysqli_fetch_array($result);
	if($row['CRN'] < 10000000) { 
		$CRN = 10000000;
	}
	else {
		$CRN=$row['CRN'] + 1;
	}	
	
	
$sql2 = "INSERT INTO TeachingInfo (CRN, SNum, FirstName, LastName, Subject, ContactHours, CourseCreditsHold)
VALUES ('$CRN','$SNum','$FirstName','$LastName','$Prefix','0','0')";

if (mysqli_query($con, $sql2)){

	echo "New record created successfully";}
	else {
    echo "Error: " . $sql2 . "<br>" . mysqli_error($con);
}

}

// Form
else {

echo '<form role="form" method="post" action="add_user.php">';
echo '<div class="form-group">';
echo '<label for="Prefix">Prefix:</label>';
echo '<select class="form-control" id="Prefix" name="Prefix">';
  $deptList = "AAA ASE ACC ANT ART ASL AST BIO BTE BUS CCR CHE CIS CNG COM CRJ CSC CWB DAN DPM ECE ECO EDU EGG EMS ENG ESL ETH FST FVM GEO GEY HIS HPR HUM HWE JRD LEA LIT MAN MAR MAT MGD MUS NUA PAR PED PHI PHY POS PSM PSY REE SCI SOC SPA THE TRI WST";
  $deptsplit=explode(" ",$deptList);
  foreach($deptsplit as $value) {
  echo '<option value="';
echo $value;
echo '">';
echo $value;
echo '</option>';
}
echo '</select></div>';

echo '<div class="form-group">';
echo '<label for="SNum">SNum:</label>';
echo '<input type="text" class="form-control" name="SNum" id="SNum">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="FirstName">FirstName:</label>';
echo '<input type="text" class="form-control" name="FirstName" id="FirstName">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="LastName">LastName:</label>';
echo '<input type="text" class="form-control" name="LastName" id="LastName">';
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
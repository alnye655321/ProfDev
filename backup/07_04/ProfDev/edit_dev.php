<?php
include 'id_verify.php'; //$user included as S# from cookie
include 'connect.php';
$id = $_POST['activityID'];
$formSubmit = $_POST['formSubmit'];

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add Prof Dev</title>
	


	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
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
<h1>ProfDev Edit</h1>



<div class="container">
<?php

// SQL Add
if($formSubmit == "true"){
	
			$uploadOk = 1; $target_file = NULL;
		if($_FILES["fileToUpload"]["error"] == 0) {
			include 'upload.php';
			}

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

mysqli_query($con,"UPDATE Activity SET Type = '$Type' WHERE id = '$id'");
mysqli_query($con,"UPDATE Activity SET Item = '$Item' WHERE id = '$id'");
mysqli_query($con,"UPDATE Activity SET Date = '$Date' WHERE id = '$id'");
mysqli_query($con,"UPDATE Activity SET Sponsor = '$Sponsor' WHERE id = '$id'");
mysqli_query($con,"UPDATE Activity SET Hours = '$Hours' WHERE id = '$id'");
mysqli_query($con,"UPDATE Activity SET Comments = '$Comments' WHERE id = '$id'");
if($target_file != NULL){mysqli_query($con,"UPDATE Activity SET File = '$target_file' WHERE id = '$id'");}
echo "Record Updated";
}

// Form
else{
$result = mysqli_query($con,"SELECT * FROM Activity WHERE id = '$id'");
while($row = mysqli_fetch_array($result))
{
echo '<form role="form" method="post" action="edit_dev.php" enctype="multipart/form-data">';
echo '<div class="form-group">';
echo '<label for="SNum">SNum:</label>';
echo '<input type="text" class="form-control" name="SNum" id="SNum" value="'.$row['SNum'].'" readonly="readonly">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="LastName">LastName:</label>';
echo '<input type="text" class="form-control" name="LastName" id="LastName" value="'.$row['LastName'].'" readonly="readonly">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="FirstName">FirstName:</label>';
echo '<input type="text" class="form-control" name="FirstName" id="FirstName" value="'.$row['FirstName'].'" readonly="readonly">';
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
echo '<option>'.$row['Type'].'</option>';
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
echo '<input type="text" class="form-control" name="Item" id="Item" value="'.$row['Item'].'">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="Date">Date:</label>';
echo '<input type="text" class="form-control" name="Date" id="Date" value="'.$row['Date'].'">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="Sponsor">Sponsor:</label>';
echo '<input type="text" class="form-control" name="Sponsor" id="Sponsor" value="'.$row['Sponsor'].'">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="Hours">Hours:</label>';
echo '<input type="text" class="form-control" name="Hours" id="Hours" value="'.$row['Hours'].'">';
echo '</div>';

echo '<div class="form-group">';
echo '<label for="Comments">Comments:</label>';
echo '<input type="text" class="form-control" name="Comments" id="Comments" value="'.$row['Comments'].'">';
echo '</div>';

echo '<input type="hidden" name="formSubmit" type="text" value="true">';
echo '<input type="hidden" name="activityID" type="text" value="'.$id.'">';

echo '<p class="text-muted">';
  echo 'Existing File: <a href="'.$row['File'].'" target="_blank">'.$row['File'].'</a>';
echo '</p>';
}


?>

 <div style="position:relative;"> <!--file upload styling/script-->
 <a class='btn btn-primary' href='javascript:;'>
 Replace File...
<input type="file" name="fileToUpload" id="fileToUpload" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' 
size="40"  onchange='$("#upload-file-info").html($(this).val());'>
</a>
&nbsp;
<span class='label label-info' id="upload-file-info"></span>
</div>

<br>
<button type="submit" class="btn btn-default">Submit</button>


<?php
echo '</form>';
}
?>

</div>
</div>

</body>
</html>



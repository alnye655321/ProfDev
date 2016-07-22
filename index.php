<?php
include 'id_verify.php'; //$user included as S# from cookie
include 'connect.php';

function levelNumber($SNum) {
	global $con;
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT Level FROM Level WHERE SNum = '$SNum'"));
	$level = $getID["Level"];
	echo $level;
}

function getChairName($SNum) {
	global $con;
	global $con2;

	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT DISTINCT Subject FROM TeachingInfo WHERE SNum = '$SNum' LIMIT 1"));
	$Prefix = $getID["Subject"];

	$result = mysqli_query($con2,"SELECT Depts, Name FROM Users WHERE Chair = '1'");

	while($row = mysqli_fetch_array($result))	{
		$prefixList = $row['Depts']; $Name = $row['Name'];

		$prefixSplit=explode(" ",$prefixList);
	   foreach($prefixSplit as $value) {
	   	if($value == $Prefix) {
	   		echo $Name;
	   	}
		}
	}
}

function getDepts($SNum){
//creates sql request that only pulls pending activities within the chair's prefix list
	global $con2;
	$result = mysqli_query($con2,"SELECT * FROM Users WHERE SNum = '$SNum'");
	$row = mysqli_fetch_array($result);
	$Depts = $row['Depts'];
	$sql ="";
	$deptsplit=explode(" ",$Depts); //create array from Dept string ex) "AAA HIS PSY WST"
	foreach($deptsplit as $value) {
		$sql = $sql . "Prefix = " . "'$value'" . " || "; //iterate through prefix array values, adding sql commmands or
	}
	$sql = rtrim($sql, " |"); //rtrim targets end of line - gets rid of extra space and | added from for loop above
	$sql =  "SELECT * FROM Activity WHERE Chair is NULL AND " . "(" . $sql . ")";
	return $sql;
	//output example: SELECT * FROM Activity WHERE Chair is NULL AND (Prefix = 'AAA' || Prefix = 'HIS' || Prefix = 'PSY' || Prefix = 'WST')

}

// Set User Role Info
$result = mysqli_query($con2,"SELECT * FROM Users WHERE SNum = '$user'");
while($row = mysqli_fetch_array($result)){

	if($row['Chair'] == 1) {
		$_SESSION["role"] = "chair";
	}

	if($row['VP'] == 1) {
		$_SESSION["role"] = "VP";
	}

	if($row['Dean'] == 1) {
		$_SESSION["role"] = "dean";
	}

	if($row['Payroll'] == 1) {
		$_SESSION["role"] = "payroll";
	}

	if($row['Admin'] == 1) {
		$_SESSION["admin"] = true;
	}

}
// Close Set User Role Info

// Admin - change role to...
if($_POST['ChairChange'] && $_SESSION["admin"] == true) {
	$_SESSION["role"] = "chair";
	mysqli_query($con2,"UPDATE Users SET Chair = '1' WHERE SNum = '$user'");
	mysqli_query($con2,"UPDATE Users SET VP = NULL WHERE SNum = '$user'");
	mysqli_query($con2,"UPDATE Users SET Dean = NULL WHERE SNum = '$user'");
	mysqli_query($con2,"UPDATE Users SET Payroll = NULL WHERE SNum = '$user'");
}

if($_POST['VPChange'] && $_SESSION["admin"] == true) {
	$_SESSION["role"] = "VP";
	mysqli_query($con2,"UPDATE Users SET Chair = NULL WHERE SNum = '$user'");
	mysqli_query($con2,"UPDATE Users SET VP = '1' WHERE SNum = '$user'");
	mysqli_query($con2,"UPDATE Users SET Dean = NULL WHERE SNum = '$user'");
	mysqli_query($con2,"UPDATE Users SET Payroll = NULL WHERE SNum = '$user'");
}

if($_POST['DeanChange'] && $_SESSION["admin"] == true) {
	$_SESSION["role"] = "dean";
	mysqli_query($con2,"UPDATE Users SET Chair = NULL WHERE SNum = '$user'");
	mysqli_query($con2,"UPDATE Users SET VP = NULL WHERE SNum = '$user'");
	mysqli_query($con2,"UPDATE Users SET Dean = '1' WHERE SNum = '$user'");
	mysqli_query($con2,"UPDATE Users SET Payroll = NULL WHERE SNum = '$user'");
}

if($_POST['PayrollChange'] && $_SESSION["admin"] == true) {
	$_SESSION["role"] = "payroll";
	mysqli_query($con2,"UPDATE Users SET Chair = NULL WHERE SNum = '$user'");
	mysqli_query($con2,"UPDATE Users SET VP = NULL WHERE SNum = '$user'");
	mysqli_query($con2,"UPDATE Users SET Dean = NULL WHERE SNum = '$user'");
	mysqli_query($con2,"UPDATE Users SET Payroll = '1' WHERE SNum = '$user'");
}

// Close Admin - change role to...


$Display = $_SESSION["Display"]; // set dept based on session
if(!empty($_POST['displayChange'])) {
	$_SESSION["Display"] = $_POST['TableDisplay']; //or change based on submission
	$Display = $_POST['TableDisplay'];
}


$dept = $_SESSION["dept"]; // set dept based on session
if(!empty($_POST['dept'])) {
	$_SESSION["dept"] = $_POST['dept']; //or change based on submission
	$dept = $_POST['dept'];
}

$nameSearch = $_POST['nameSearch'];
if(!empty($nameSearch)){
	$splitFirst = explode(", ", $nameSearch);
	$LastName = $splitFirst[0];
	$FirstName = $splitFirst[1];
}

$SNum = $_POST['searchSNum'];




?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>CCA Professional Development</title>

	<link rel="stylesheet" href="main.css">

	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="js/tether.min.js"></script>  <!--tooltip-->
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


//TableSorter Settings
	<script id="js">$(function() {

	// NOTE: $.tablesorter.theme.bootstrap is ALREADY INCLUDED in the jquery.tablesorter.widgets.js
	// file; it is included here to show how you can modify the default classes
	$.tablesorter.themes.bootstrap = {
		// these classes are added to the table. To see other table classes available,
		// look here: http://getbootstrap.com/css/#tables
		table        : 'table table-bordered table-striped',
		caption      : 'caption',
		// header class names
		header       : 'bootstrap-header', // give the header a gradient background (theme.bootstrap_2.css)
		sortNone     : '',
		sortAsc      : '',
		sortDesc     : '',
		active       : '', // applied when column is sorted
		hover        : '', // custom css required - a defined bootstrap style may not override other classes
		// icon class names
		icons        : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
		iconSortNone : 'bootstrap-icon-unsorted', // class name added to icon when column is not sorted
		iconSortAsc  : 'glyphicon glyphicon-chevron-up', // class name added to icon when column has ascending sort
		iconSortDesc : 'glyphicon glyphicon-chevron-down', // class name added to icon when column has descending sort
		filterRow    : '', // filter row class; use widgetOptions.filter_cssFilter for the input/select element
		footerRow    : '',
		footerCells  : '',
		even         : '', // even row zebra striping
		odd          : ''  // odd row zebra striping
	};

	// call the tablesorter plugin and apply the uitheme widget
	$("table").tablesorter({
		// this will apply the bootstrap theme if "uitheme" widget is included
		// the widgetOptions.uitheme is no longer required to be set
		theme : "bootstrap",

		widthFixed: true,

		headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

		// widget code contained in the jquery.tablesorter.widgets.js file
		// use the zebra stripe widget if you plan on hiding any rows (filter widget)
		widgets : [ "uitheme", "filter", "zebra" ],

		widgetOptions : {
			// using the default zebra striping class name, so it actually isn't included in the theme variable above
			// this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
			zebra : ["even", "odd"],

			// reset filters button
			filter_reset : ".reset",

			// extra css class name (string or array) added to the filter element (input or select)
			filter_cssFilter: "form-control",

			// set the uitheme widget to use the bootstrap theme class names
			// this is no longer required, if theme is set
			// ,uitheme : "bootstrap"

		}
	})
	.tablesorterPager({

		// target the pager markup - see the HTML block below
		container: $(".ts-pager"),

		// target the pager page select dropdown - choose a page
		cssGoto  : ".pagenum",

		// remove rows from the table to speed up the sort of large tables.
		// setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
		removeRows: false,

		// output string - default is '{page}/{totalPages}';
		// possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
		output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'

	});

});</script>

	<script>
	$(function(){

		// filter button demo code
		$('button.filter').click(function(){
			var col = $(this).data('column'),
				txt = $(this).data('filter');
			$('table').find('.tablesorter-filter').val('').eq(col).val(txt);
			$('table').trigger('search', false);
			return false;
		});


	});
	</script>

<!-- jQuery UI autocomplete -->

	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

	<script>
	$(function() {
	    $( "#nameSearch" ).autocomplete({
	        source: 'search.php',
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


<style>
.changeCursor {
cursor:pointer;
display: block;
margin-left: auto;
margin-right: auto;
}

.changeCursor1 {
cursor:pointer;

}
</style>

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
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">Add<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="add_dev.php">Add ProfDev</a></li>
                <li><a href="add_event.php">Add Event</a></li>
                <li><a href="add_user.php">Add missing user</a></li>
<!--                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>-->
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






<!-- Main Table Div -->
<div id="main">

<br><br>

	<h1>ProfDev <a href="add_dev.php"><span class="glyphicon glyphicon-plus" style="color:blue"></span></a></h1>
	<!-- use the filter_reset : '.reset' option or include data-filter="" using the filter button demo code to reset the filters -->



	<br>

	<div class="row">
<!-- Dept Select -->
<div class="col-md-1"><form method="post" action="index.php">
<select onchange="this.form.submit()" name ="dept" class="form-control">
  <?php
	if(!empty($dept)){
  echo '<option value="'.$dept.'">'.$dept.'</option>';
}
  //$deptList = "AAA ASE ACC ANT ART ASL AST BIO BTE BUS CCR CHE CIS CNG COM CRJ CSC CWB DAN DPM ECE ECO EDU EGG EMS ENG ESL ETH FST FVM GEO GEY HIS HPR HUM HWE JRD LEA LIT MAN MAR MAT MGD MUS NUA PAR PED PHI PHY POS PSM PSY REE SCI SOC SPA THE TRI WST";
	$result = mysqli_query($con2,"SELECT * FROM Users WHERE SNum = '$user'"); // get Dept list from Users table
	$row = mysqli_fetch_array($result);
	$Depts = $row['Depts'];
	$deptsplit=explode(" ",$Depts);//split into array and run options with each 3 letter subject prefix
	foreach($deptsplit as $value) {
	echo '<option value="';
	echo $value;
	echo '">';
	echo $value;
	echo '</option>';
}
  ?>

</select>
</form>
</div>
<!-- End Dept Select -->

<?php
echo '
<div class="col-md-1">
	<form method="post" action="index.php">
	<input type="hidden" name="TableDisplay" type="text" value="Activity">
	<input type="hidden" name="displayChange" type="text" value="true">
	<input type="hidden" name="dept" type="text" value="'.$dept.'">
	<input type="submit" class="btn btn-default btn-block inactive" id="Activity" value="Activity">
</form></div>

<div class="col-md-1">
	<form method="post" action="index.php">
	<input type="hidden" name="TableDisplay" type="text" value="Levels">
	<input type="hidden" name="displayChange" type="text" value="true">
	<input type="hidden" name="dept" type="text" value="'.$dept.'">
	<input type="submit" class="btn btn-default btn-block inactive" id="Levels" value="Status">
</form></div>

<div class="col-md-1">
	<form method="post" action="index.php">
	<input type="hidden" name="TableDisplay" type="text" value="Pending">
	<input type="hidden" name="displayChange" type="text" value="true">
	<input type="hidden" name="dept" type="text" value="'.$dept.'">
	<input type="submit" class="btn btn-default btn-block inactive" color="blue" id="Pending" value="Pending">
</form></div>

<div class="col-md-1">
	<form method="post" action="index.php">
	<input type="hidden" name="TableDisplay" type="text" value="Payroll">
	<input type="hidden" name="displayChange" type="text" value="true">
	<input type="hidden" name="dept" type="text" value="'.$dept.'">
	<input type="submit" class="btn btn-default btn-block inactive" color="blue" id="Payroll" value="Advance">
</form></div>
';
?>


	<div class="col-md-2"><form method="post" action="index.php">
        <div class="input-group">
            <input type="text" class="form-control ui-widget" placeholder="Search LastName" name="nameSearch" id="nameSearch">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
     </form></div>

  <div class="col-md-2"><form method="post" action="index.php">
        <div class="input-group">
            <input type="text" class="form-control ui-widget" placeholder="Search SNum" name="searchSNum" id="searchSNum">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
     </form></div>

      <div class="col-md-1">
<?php
		//Export Button
		if($Display == "Activity"){
		echo '<form method="post" action="export.php" onClick="this.submit();" class="changeCursor1">
		<input type="hidden" name="Activity" type="text" value="true">
		<input type="hidden" name="Prefix" type="text" value="'.$dept.'">
		<span class="glyphicon glyphicon-download-alt" style="color:green; font-size:2em;"></span>
		</form>';
		}

		if($Display == "Levels"){
		echo '<form method="post" action="export.php" onClick="this.submit();" class="changeCursor1">
		<input type="hidden" name="Level" type="text" value="true">
		<span class="glyphicon glyphicon-download-alt" style="color:green; font-size:2em;"></span>
		</form>';
		}
		//Close Export Button
?>
	   </div>
</div>



<?php
// Activity Table


if($Display == "Activity")
{
	include 'activity.php';
}
//Close Activity Table
?>

<?php
// Levels Table


if($Display == "Levels"){
	include 'levels.php';
}
//Close Levels Table
?>

<?php
// Pending Table
//?????????????????????? Add different $results based on user name. Admin (Sharon, VP) select all pending records. Chairs just their Dept
//?????????????????????? change Dean value below
if($Display == "Pending"){
	include 'pending.php';
}
//Close Pending Table
?>

<?php
// Payroll Table
// Payroll = "Advance" in user display
if($Display == "Payroll"){
	include 'payroll.php';
}
//Close Payroll Table
?>

	<div class="bootstrap_buttons">
		Reset filter : <button type="button" class="reset btn btn-primary" data-column="0" data-filter=""><i class="icon-white icon-refresh glyphicon glyphicon-refresh"></i> Reset filters</button>
	</div>
</div> <!-- End Main Table Div -->

<div class="container">

  <div class="page-header">
        <h1>Info</h1>
      </div>
      <div class="row">
        <div class="col-sm-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Current Role: <?php echo $_SESSION["role"]; ?></h3>
            </div>
            <div class="panel-body">
            <?php
            echo '<form method="post" action="index.php">';

	            echo '<p><strong>Change Role To:</strong></p>

	              <div class="checkbox">
				    <label>
				      <input type="checkbox" name="ChairChange"> Chair
				    </label>
				  </div>
				  <div class="checkbox">
				    <label>
				      <input type="checkbox" name="VPChange"> VP
				    </label>
				  </div>
				  <div class="checkbox">
				    <label>
				      <input type="checkbox" name="DeanChange"> Dean
				    </label>
				  </div>
				  <div class="checkbox">
				    <label>
				      <input type="checkbox" name="PayrollChange"> Payroll
				    </label>
				  </div>';
				  echo '<button type="submit" class="btn btn-default">Submit</button>';

            echo '</form>';

            ?>
            </div>
          </div>
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Panel title</h3>
            </div>
            <div class="panel-body">
              Panel content
            </div>
          </div>
        </div><!-- /.col-sm-4 -->
        <div class="col-sm-4">
          <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title">Panel title</h3>
            </div>
            <div class="panel-body">
              Panel content
            </div>
          </div>
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Panel title</h3>
            </div>
            <div class="panel-body">
              Panel content
            </div>
          </div>
        </div><!-- /.col-sm-4 -->
        <div class="col-sm-4">
          <div class="panel panel-warning">
            <div class="panel-heading">
              <h3 class="panel-title">Panel title</h3>
            </div>
            <div class="panel-body">
              Panel content
            </div>
          </div>
          <div class="panel panel-danger">
            <div class="panel-heading">
              <h3 class="panel-title">Panel title</h3>
            </div>
            <div class="panel-body">
              Panel content
            </div>
          </div>
        </div><!-- /.col-sm-4 -->
      </div>

</div>

<!-- Activity Info Modal -->
<!--<div class="modal2" id="Info" style="display:none;"></div>-->

<div id="Info" class="modal fade" tabindex="-1" role="dialog">

<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Activity Info</h4>
      </div>
      <div class="modal-body">


<div id="replaceInfo"></div> <!--Replace with description list from ajax-->

      </div>
      <div class="modal-footer">
        <form method="post" action="edit_dev.php"> <!--Pass row ID back from ajax to send to edit activity form-->
        <input type="hidden" name="editActivitySubmit" type="text" value="true"><div id="editActivity"></div>
      <button type="submit" class="btn btn-primary">Edit</button></form>
      </div>
    </div>
  </div>
</div>

<!-- Close Activity Info Modal -->

<!--Modal Chair Rec-->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog">

<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Chair Rec</h4>
      </div>
      <div class="modal-body">
<form class="modalSubmit">



<div id="replace"></div>

<input type="hidden" name="modalSubmit" type="text" value="true">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" id="modalButton" class="btn btn-primary">Save changes</button></form>
      </div>
    </div>
  </div>

</div>
<!--Close Modal Chair Rec-->


<!--Modal Override-->
<div id="overrideModal" class="modal fade" tabindex="-1" role="dialog">

<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Override</h4>
      </div>
      <div class="modal-body">
<form class="modalSubmit">



<div id="overrideReplace"></div>

<input type="hidden" name="overrideModalSubmit" type="text" value="true">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button></form>
      </div>
    </div>
  </div>

</div>
<!--Close Modal Override-->

<!--Modal Level Info (in)active set-->
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog">

<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Active Status</h4>
      </div>
      <div class="modal-body">
<form class="modalSubmit">



<div id="replace2"></div>

<input type="hidden" name="lvl_info_submit" type="text" value="true">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" id="modalButton" class="btn btn-primary">Save changes</button></form>
      </div>
    </div>
  </div>

</div>
<!--Close Modal Level Info (in)active set-->

<!-- Pending Approval AJAX -->
	<script>
	$('.form1').change(function(event) {
		event.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'ajax.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if(data.type == 'ChairdenyRemove')	{
						var denyID =  data.msg;
						$('#' + denyID).prop("checked", false);
						}

				if(data.type == 'VPdenyRemove')	{
						var denyID =  data.msg;
						$('#' + denyID).prop("checked", false);
						}


			}
		});
	});
	</script>

	<!-- Additional Info AJAX -->
		<script>
	$('.form2').click(function(event) {
		event.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'ajax.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if(data.type == 'Info') {$('#replaceInfo').html(data.msg); $('#editActivity').html(data.activityID); $('#Info').modal('show');}

			}
		});
	});
	</script>


		<!-- Modal Chair Rec Form Generate -->
		<script>
	$('.changeCursor1').click(function(event) {
		event.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'ajax.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if(data.type == 'Info') {$('#replace').html(data.msg); $('#myModal').modal('show');}
				if(data.type == 'lvl_info') {$('#replace2').html(data.msg); $('#myModal2').modal('show');}


			}
		});
	});
	</script>

			<!-- AJAX Modal Form Submit -->
		<script>
$('.modalSubmit').submit(function(event) {
		event.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'ajax.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (data) {
				console.log(data);
					if(data.type == 'ChairRec2Complete')	{
						var SNumID =  data.SNumID;
						$('#' + SNumID).removeClass('glyphicon glyphicon-folder-open').addClass('glyphicon glyphicon-check').css('color','green');
						}

					if(data.type == 'ChairRec3Complete')	{
						var SNumID =  data.SNumID;
						$('#' + SNumID).removeClass('glyphicon glyphicon-folder-open').addClass('glyphicon glyphicon-check').css('color','green');
						}

					$('#myModal').modal('hide');
					$('#myModal2').modal('hide');

			}
		});
	});
	</script>


<script>
function hideDiv() {
   document.getElementById('Info').style.display = "none";
   document.getElementById('DetailInfo').style.display = "none";
}
</script>






</body>
</html>

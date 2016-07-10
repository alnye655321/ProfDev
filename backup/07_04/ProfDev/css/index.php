<?php
include 'connect.php';

if(isset($_COOKIE["ID_Data"])){
	$Display = $_COOKIE['ID_Data'];
}

//Set Cookies displayChange
$cookieChange = $_POST['displayChange'];
if($cookieChange == "true"){
	$hour = time() + 14400;
	$Display = $_POST['TableDisplay'];
	setcookie("ID_Data", $Display, $hour);
//End Set Cookies
}


$nameSearch = $_POST['nameSearch'];
$SNum = $_POST['searchSNum'];
$dept = $_POST['dept'];

// SemCalc($SNum, 2);
// Add Total Semesters Teaching && Credit/Contact Hours
function SemCalc($SNumX, $lvl)
{
	global $con;	
	
	$result = mysqli_query($con,"SELECT DISTINCT Semester FROM TeachingInfo WHERE SNum = '$SNumX'"); // get # of semesters taught
	$row_cnt = mysqli_num_rows($result);	
	
	$CreditsTotal = 0; $ContactTotal = 0;
	$result1 = mysqli_query($con,"SELECT ContactHours, CourseCreditsHold FROM TeachingInfo WHERE SNum = '$SNumX'");	
		while($row = mysqli_fetch_array($result1))
		{
			$ContactTotal = $ContactTotal + $row['ContactHours'];
			$CreditsTotal = $CreditsTotal + $row['CourseCreditsHold'];
		}
	
	//Level 2 = 4 semesters && 12 credit hours or 180 contact hours
	if($lvl == 2)
	{
		if($row_cnt >= 4 && ($ContactTotal >= 180 || $CreditsTotal >= 12) )
			{
				echo 'X';
			}
	}
	//Level 3 = 6 semesters && 24 credit hours or 360 contact hours
	if($lvl == 3)
	{
		if($row_cnt >= 6 && ($ContactTotal >= 360 || $CreditsTotal >= 24) )
			{
				echo 'X';
			}
	}


}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>CCA Professional Development</title>
	


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
          <a class="navbar-brand" href="#">Theme</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">ProfDev</a></li>
            <li><a href="#about">Credentials</a></li>
            <li><a href="#contact">PlaceHolder</a></li>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">Add<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="add_dev.php">Add ProfDev</a></li>
                <li><a href="#">Another action</a></li>
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




<script>$('.success').hide();</script>
<p class="success">Updated</p>

<div id="main"> <!-- Main Table Div -->

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
  $deptList = "AAA ASE ACC ANT ART ASL AST BIO BTE BUS CCR CHE CIS CNG COM CRJ CSC CWB DAN DPM ECE ECO EDU EGG EMS ENG ESL ETH FST FVM GEO GEY HIS HPR HUM HWE JRD LEA LIT MAN MAR MAT MGD MUS NUA PAR PED PHI PHY POS PSM PSY REE SCI SOC SPA THE TRI WST";
  $deptsplit=explode(" ",$deptList);
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
	<input type="submit" class="btn btn-default btn-block inactive" id="Levels" value="Levels">
</form></div>

<div class="col-md-1">
	<form method="post" action="index.php">
	<input type="hidden" name="TableDisplay" type="text" value="Pending">
	<input type="hidden" name="displayChange" type="text" value="true">
	<input type="hidden" name="dept" type="text" value="'.$dept.'">
	<input type="submit" class="btn btn-default btn-block inactive" color="blue" id="Pending" value="Pending">
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
     
     
	</div>

<?php
// Activity Table


if($Display == "Activity")
{
	echo '<script>document.getElementById("'.$Display.'").className = "btn btn-primary btn-block active";</script>';
//bootstrap classes added by the uitheme widget
	echo '<div id="demo"><table> 
	<thead>
		<tr>
			<th>SNum</th>
			<th>Name</th>
			<th>Type</th>
			<!--<th class="filter-select filter-exact" data-placeholder="Pick a gender">Sex</th>-->
			<th>Item</th>
			<th>Date</th>
			<th>Sponsor</th>
			<th>Content</th>
			<th>Hours</th></tr>
	</thead>
	<tfoot>
		<tr>
			<th>SNum</th>
			<th>Name</th>
			<th>Type</th>
			<!--<th>Sex</th>-->
			<th>Item</th>
			<th>Date</th>
			<th>Sponsor</th>
			<th>Content</th>
			<th>Hours</th>
		</tr>
		<tr>
			<th colspan="8" class="ts-pager form-horizontal">
				<button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
				<button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
				<span class="pagedisplay"></span> <!-- this can be any element, including an input -->
				<button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
				<button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
				<select class="pagesize input-mini" title="Select page size">
					<option selected="selected" value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
				</select>
				<select class="pagenum input-mini" title="Select page number"></select>
			</th>
		</tr>
	</tfoot>

	<tbody>';


if (!empty($SNum)){
	$result = mysqli_query($con,"SELECT * FROM Activity WHERE SNum = '$SNum'");
}

	else if(!empty($nameSearch)) {
		$result = mysqli_query($con,"SELECT * FROM Activity WHERE LastName = '$nameSearch'");
	}
	
		else {
			$result = mysqli_query($con,"SELECT * FROM Activity WHERE Prefix = '$dept'");
		}

// Table Body SQL Select
	while($row = mysqli_fetch_array($result))
	{
		echo "<tr>";
		echo "<td>" . $row['SNum'] . "</td>";
		echo '<td>'.$row['LastName'].', '. $row['FirstName'].'</td>';
		echo "<td>" . $row['Type'] . "</td>";
		echo "<td>" . $row['Item'] . "</td>";
		echo "<td>" . $row['Date'] . "</td>";
		echo "<td>" . $row['Sponsor'] . "</td>";
		echo "<td>" . $row['Content'] . "</td>";
		echo "<td>" . $row['Hours'] . "</td>";
		echo "</tr>";
	}




echo' </tbody>';
	

echo '</table></div>';
}
//Close Activity Table
?>

<?php
// Levels Table


if($Display == "Levels"){

echo '<script>document.getElementById("'.$Display.'").className = "btn btn-primary btn-block active";</script>';	
//bootstrap classes added by the uitheme widget
	echo '<div id="demo"><table> 
	<thead>
		<tr>
			<th>SNum</th>
			<th>Name</th>
			<th>Level</th>
			<th>D2L(1)</th>			
			<th>NFO(1)</th>
			<th>EDU222(2)</th>
			<th>4SemTeach(2)</th>
			<th>ChairRec(2)</th>
			<th>6SemTeach(3)</th>
			<th>15hrProfDev(3)</th>
			<th>ChairRec(3)</th>
			
	</thead>
	<tfoot>
		<tr>
			<th>SNum</th>
			<th>Name</th>
			<th>Level</th>
			<th>D2L(1)</th>			
			<th>NFO(1)</th>
			<th>EDU222(2)</th>
			<th>4SemTeach(2)</th>
			<th>ChairRec(2)</th>
			<th>6SemTeach(3)</th>
			<th>15hrProfDev(3)</th>
			<th>ChairRec(3)</th>
		</tr>
		<tr>
			<th colspan="11" class="ts-pager form-horizontal">
				<button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
				<button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
				<span class="pagedisplay"></span> <!-- this can be any element, including an input -->
				<button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
				<button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
				<select class="pagesize input-mini" title="Select page size">
					<option selected="selected" value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
				</select>
				<select class="pagenum input-mini" title="Select page number"></select>
			</th>
		</tr>
	</tfoot>

	<tbody>';


if (!empty($SNum)){
	$result = mysqli_query($con,"SELECT * FROM Level WHERE SNum = '$SNum'");
}

	else if(!empty($nameSearch)) {
		$result = mysqli_query($con,"SELECT * FROM Level WHERE LastName = '$nameSearch'");
	}
	
		else {
			$result = mysqli_query($con,"SELECT * FROM Level WHERE Prefix = '$dept'");
		}

$CheckImage = '<span style="font-size:1.5em; color:green;" class="glyphicon glyphicon-check"></span>';
$UnCheckImage = '<span style="font-size:1.5em; color:red;" class="glyphicon glyphicon-unchecked"></span>';

// Table Body SQL Select
	while($row = mysqli_fetch_array($result))
	{
		echo "<tr>";
		echo "<td style='font-size:1.5em;'>" . $row['SNum'] . "</td>";
		echo '<td>'.$row['LastName'].', '. $row['FirstName'].'</td>';
		echo "<td style='font-size:1.5em;'>" . $row['Level'] . "</td>";
		echo "<td style='text-align:center;'>"; if($row['D2L'] == 1) {echo $CheckImage;} else {echo $UnCheckImage;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['NFO'] == 1) {echo $CheckImage;} else {echo $UnCheckImage;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['EDU222'] == 1) {echo $CheckImage;} else {echo $UnCheckImage;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['4SemTeach'] == 1) {echo $CheckImage;} else {echo $UnCheckImage;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['ChairRec2'] == 1) {echo $CheckImage;} else {echo $UnCheckImage;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['6SemTeach'] == 1) {echo $CheckImage;} else {echo $UnCheckImage;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['15hrProfDev'] == 1) {echo $CheckImage;} else {echo $UnCheckImage;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['ChairRec3'] == 1) {echo $CheckImage;} else {echo $UnCheckImage;} echo"</td>";
		echo "</tr>";
	}


echo' </tbody>';
	

echo '</table></div>';
}
//Close Levels Table
?>

<?php
// Pending Table
//?????????????????????? Add different $results based on user name. Admin (Sharon, VP) select all pending records. Chairs just their Dept

if($Display == "Pending")
{
	echo '<script>document.getElementById("'.$Display.'").className = "btn btn-primary btn-block active";</script>';
//bootstrap classes added by the uitheme widget
	echo '<div id="demo"><table> 
	<thead>
		<tr>
			<th>SNum</th>
			<th>Name</th>
			<th>Type</th>
			<!--<th class="filter-select filter-exact" data-placeholder="Pick a gender">Sex</th>-->
			<th>Item</th>
			<th>Date</th>
			<th>Sponsor</th>
			<th>Content</th>
			<th>Hours</th>
			<th>Chair</th>
			<th>VP</th>
			<th>Payroll</th>
			</tr>
	</thead>
	<tfoot>
		<tr>
			<th>SNum</th>
			<th>Name</th>
			<th>Type</th>
			<!--<th>Sex</th>-->
			<th>Item</th>
			<th>Date</th>
			<th>Sponsor</th>
			<th>Content</th>
			<th>Hours</th>
			<th>Chair</th>
			<th>VP</th>
			<th>Payroll</th>
		</tr>
		<tr>
			<th colspan="11" class="ts-pager form-horizontal">
				<button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
				<button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
				<span class="pagedisplay"></span> <!-- this can be any element, including an input -->
				<button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
				<button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
				<select class="pagesize input-mini" title="Select page size">
					<option selected="selected" value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
				</select>
				<select class="pagenum input-mini" title="Select page number"></select>
			</th>
		</tr>
	</tfoot>

	<tbody>';


if (!empty($SNum)){
	$result = mysqli_query($con,"SELECT * FROM Activity WHERE SNum = '$SNum' AND Payroll is NULL");
}

	else if(!empty($nameSearch)) {
		$result = mysqli_query($con,"SELECT * FROM Activity WHERE LastName = '$nameSearch' AND Payroll is NULL");
	}
	
		else {
			$result = mysqli_query($con,"SELECT * FROM Activity WHERE Prefix = '$dept' AND Payroll is NULL");
		}

// Table Body SQL Select
	while($row = mysqli_fetch_array($result))
	{
		if($row['Chair'] == 1){$chairCheck="checked";} else{$chairCheck = "";}
		if($row['VP'] == 1){$VPCheck="checked";} else{$VPCheck = "";}
		if($row['Payroll'] == 1){$payrollCheck="checked";} else{$payrollCheck = "";}
		echo "<tr>";
		echo "<td>" . $row['SNum'] . "</td>";
		echo '<td>'.$row['LastName'].', '. $row['FirstName'].'</td>';
		echo "<td>" . $row['Type'] . "</td>";
		echo "<td>" . $row['Item'] . "</td>";
		echo "<td>" . $row['Date'] . "</td>";
		echo "<td>" . $row['Sponsor'] . "</td>";
		echo "<td>" . $row['Content'] . "</td>";
		echo "<td>" . $row['Hours'] . "</td>";
		echo '<td><form class="form1"><input type="checkbox" '.$chairCheck.' name="Chair" value="'.$row['id'].'"></form>  </td>';
		echo '<td><form class="form1"><input type="checkbox" '.$VPCheck.' name="VP" value="'.$row['id'].'"></form>  </td>';
		echo '<td><form class="form1"><input type="checkbox" '.$payrollCheck.' name="Payroll" value="'.$row['id'].'"></form>  </td>';
		echo "</tr>";
	}




echo' </tbody>';
	

echo '</table></div>';
}
//Close Pending Table
?>

	<div class="bootstrap_buttons">
		Reset filter : <button type="button" class="reset btn btn-primary" data-column="0" data-filter=""><i class="icon-white icon-refresh glyphicon glyphicon-refresh"></i> Reset filters</button>
	</div>
</div> <!-- End Main Table Div -->

<div class="container">

  <div class="page-header">
        <h1>Panels</h1>
      </div>
      <div class="row">
        <div class="col-sm-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Panel title</h3>
            </div>
            <div class="panel-body">
              Panel content
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
				if(data.type == 'Sent') {$('.success').show(1).delay(1000).hide(1);}
			}
		});
	});
	</script>

</body>
</html>



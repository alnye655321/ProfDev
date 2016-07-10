<?php
include 'id_verify.php'; //$user included as S# from cookie
include 'connect.php';

// functions
function numberAttending($id) {
	global $con;
	
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM Events WHERE id = '$id'"));
	$Regs = $getID["Regs"]; // find S#s of database saved registrants. string separated by " "
	if($Regs != NULL) { // if there are any participants, build array from S#s and count
		$arrayJoin = explode(" ", $Regs); //explode saved S#s into string
		$amount = count($arrayJoin);
		echo "$amount";
	}
	
}


function displayName($SNumVal) {
	global $con; global $LastName; global $FirstName;
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT DISTINCT LastName, FirstName FROM TeachingInfo WHERE SNum = '$SNumVal'"));
	$LastName = $getID["LastName"];
	$FirstName = $getID["FirstName"];
		
}
// close functions


$nameSearch = $_POST['nameSearch'];
$SNum = $_POST['searchSNum'];
$dept = $_POST['dept'];
$completeEvent = $_POST['completeEvent'];


if($completeEvent == "true") {
	$id = $_POST['id'];
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM Events WHERE id = '$id'"));
	
	$SNumCreator = $getID["SNum"]; $Type = $getID["Type"]; $Item = $getID["Item"]; $Date = $getID["Date"]; $Sponsor = $getID["Sponsor"]; 
	$Hours = $getID["Hours"]; $Comments = $getID["Comments"]; //get all info from event
	
	$Regs = $getID["Regs"]; // find S#s of database saved registrants. string separated by " "
	if($Regs != NULL){
		$arrayJoin = explode(" ", $Regs); //explode saved S#s into string
		foreach ($arrayJoin as $SNumX) {
			displayName($SNumX); // get first and last names
			
			$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT DISTINCT Subject FROM TeachingInfo WHERE SNum = '$SNumX' LIMIT 1")); // get prefix
			$Prefix = $getID["Subject"];

			$result = mysqli_query($con,"SELECT id FROM Activity ORDER BY id DESC LIMIT 1"); // get new update id (largest +1) from activity table
			$row = mysqli_fetch_array($result);
			$idActivity=$row['id'] + 1;
			
			$sql2 = "INSERT INTO Activity (id, SNum, LastName, FirstName, Type, Item, Date, Sponsor, Hours, Prefix, Comments)
			VALUES ('$idActivity','$SNumX','$LastName','$FirstName','$Type','$Item','$Date','$Sponsor','$Hours','$Prefix','$Comments')";
			// ++ email chairs
			if (mysqli_query($con, $sql2)){echo "New record created successfully";}
			else {echo "Error: " . $sql2 . "<br>" . mysqli_error($con);}
			
			$today = date("Y/m/d");
			mysqli_query($con,"UPDATE Events SET Complete = '$today' WHERE id = '$id'"); //set completed event date as today
		}
	}
}





?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>CCA Professional Development Events</title>
	
	<link rel="stylesheet" href="main.css">

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
            <li><a href="#contact">PlaceHolder</a></li>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">Add<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="add_dev.php">Add ProfDev</a></li>
                <li><a href="add_event.php">Add Event</a></li>
                <li><a href="add_user.php">Add missing user</a></li>
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






<!-- Main Table Div -->
<div id="main"> 

<br><br>

	<h1>Events <a href="add_event.php"><span class="glyphicon glyphicon-plus" style="color:blue"></span></a></h1>
	<!-- use the filter_reset : '.reset' option or include data-filter="" using the filter button demo code to reset the filters -->
	


	<br>
	


<?php
// Events Table


	echo '<div id="demo"><table> 
	<thead>
		<tr>
			<th></th>
			<th>Creator</th>
			<th>Type</th>
			<th>Item</th>
			<th>Date</th>			
			<th>Sponsor</th>
			<th>Hours</th>
			<th>Attending</th>
			<th>Complete</th>
			</tr>
	</thead>
	<tfoot>
		<tr>
			<th></th>
			<th>Creator</th>
			<th>Type</th>
			<th>Item</th>
			<th>Date</th>			
			<th>Sponsor</th>
			<th>Hours</th>
			<th>Attending</th>
			<th>Complete</th>
			</tr>
		<tr>
			<th colspan="9" class="ts-pager form-horizontal">
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


$result = mysqli_query($con,"SELECT * FROM Events WHERE id != '1' ORDER BY Date");


// Table Body SQL Select
	while($row = mysqli_fetch_array($result))
	{
		echo "<tr>";
		echo "<td>";
			echo '<form method="post" action="edit_event.php" onClick="this.submit();"><input type="hidden" name="eventID" type="text" value="'.$row['id'].'">
			<input type="hidden" name="InfoCheck" type="text" value="true">
			<img class="changeCursor" src="images/transp-inf.png" /></form>';
		echo "</td>";
		echo "<td>" . $row['SNum'] . "</td>";
		echo "<td>" . $row['Type'] . "</td>";
		echo "<td>" . $row['Item'] . "</td>";
		echo "<td>" . $row['Date'] . "</td>";
		echo "<td>" . $row['Sponsor'] . "</td>";
		echo "<td>" . $row['Hours'] . "</td>";
		echo "<td>"; 
			numberAttending($row['id']);
		echo "</td>";
		echo '<td>'; // Complete icons & complete submit rows
			if($row['Complete'] == NULL) {
				echo '<span style="font-size:1.5em; color:red; display: block; text-align:center;" class="glyphicon glyphicon-unchecked"></span>';
			}
			else {
				echo '<span class="glyphicon glyphicon-check" style="font-size:1.5em; display: block; text-align:center; color:green;"></span>';
			}
		echo '</td>';
		echo "</tr>";
	}




echo' </tbody>';
	

echo '</table></div>';

//Close Events Table
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

<!-- Popup Divs -->	
<div class="modal2" id="Info" style="display:none;"></div>

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
			url: 'ajax_info.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if(data.type == 'Info') {$('#Info').html(data.msg); document.getElementById("Info").style.display = "inline";}
				if(data.type == 'DetailInfo') {$('#DetailInfo').html(data.msg); document.getElementById("DetailInfo").style.display = "inline";}
				
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
			url: 'ajax_info.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if(data.type == 'Info') {$('#myModal').html(data.msg); $('#myModal').modal('show');}
				
				
			}
		});
	});
	</script>
	
			<!-- Modal Chair Rec Form Submit -->
		<script>
	$('.modalSubmit').click(function(event) {
		event.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'ajax2.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (data) {
				console.log(data);
						
				
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

<div id="myModal" class="modal fade" tabindex="-1" role="dialog"></div>




</body>
</html>



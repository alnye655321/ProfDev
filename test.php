<?php
echo '
<div class="col-md-1">
	<form method="post" action="index.php">
	<input type="hidden" name="TableDisplay" type="text" value="Activity">
	<input type="hidden" name="dept" type="text" value="'.$dept.'">
	<input type="submit" class="btn btn-default btn-block inactive" id="Activity" value="Activity">
</form></div>

<div class="col-md-1">
	<form method="post" action="index.php">
	<input type="hidden" name="TableDisplay" type="text" value="Levels">
	<input type="hidden" name="dept" type="text" value="'.$dept.'">
	<input type="submit" class="btn btn-default btn-block inactive" id="Levels" value="Levels">
</form></div>

<div class="col-md-1">
	<form method="post" action="index.php">
	<input type="hidden" name="TableDisplay" type="text" value="Pending">
	<input type="hidden" name="dept" type="text" value="'.$dept.'">
	<input type="submit" class="btn btn-default btn-block inactive" color="blue" id="Pending" value="Pending">
</form></div>
';








// Activity Table


if($Display == "Activity")
{
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
			<th colspan="7" class="ts-pager form-horizontal">
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


if (!empty($Display)) {
echo '<script>document.getElementById("'.$Display.'").className = "btn btn-primary btn-block active";</script>';
// Table Body SQL Select
$result = mysqli_query($con3,"SELECT * FROM Activity WHERE Prefix = '$dept'");
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
}

if (!empty($SNum)) {
// Table Body SQL Select
$result = mysqli_query($con3,"SELECT * FROM Activity WHERE SNum = '$SNum'");
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
}

echo' </tbody>';
	

echo '</table></div>';
}
//Close Activity Table





?>


<div class="col-md-1"><form method="post" action="index.php"><input type="hidden" name="TableDisplay" type="text" value="Activity"><input type="submit" class="btn btn-default btn-block inactive" id="Activity" value="Activity"></form></div>
	<div class="col-md-1"><form method="post" action="index.php"><input type="hidden" name="TableDisplay" type="text" value="Levels"><input type="submit" class="btn btn-default btn-block inactive" id="Levels" value="Levels"></form></div>
	<div class="col-md-1"><form method="post" action="index.php"><input type="hidden" name="TableDisplay" type="text" value="Pending"><input type="submit" class="btn btn-default btn-block inactive" color="blue" id="Pending" value="Pending"></form></div>
	
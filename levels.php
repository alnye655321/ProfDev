<?php
echo '<script>document.getElementById("'.$Display.'").className = "btn btn-primary btn-block active";</script>';
//bootstrap classes added by the uitheme widget
	echo '<div id="demo"><table>
	<thead>
		<tr>
			<th>Info</th>
			<th>SNum</th>
			<th>Name</th>
			<th>Level</th>
			<th>D2L(R)</th>
			<th>NFO(R)</th>
			<th>EDU222(2)</th>
			<th>4SemTeach(2)</th>
			<th>ChairRec(2)</th>
			<th>6SemTeach(3)</th>
			<th>15hrProfDev(3)</th>
			<th>ChairRec(3)</th>

	</thead>
	<tfoot>
		<tr>
			<th>Info</th>
			<th>SNum</th>
			<th>Name</th>
			<th>Level</th>
			<th>D2L(R)</th>
			<th>NFO(R)</th>
			<th>EDU222(2)</th>
			<th>4SemTeach(2)</th>
			<th>ChairRec(2)</th>
			<th>6SemTeach(3)</th>
			<th>15hrProfDev(3)</th>
			<th>ChairRec(3)</th>
		</tr>
		<tr>
			<th colspan="12" class="ts-pager form-horizontal">
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

// table select options
if (!empty($SNum)){
	$result = mysqli_query($con,"SELECT * FROM Level WHERE SNum = '$SNum'");
}

	else if(!empty($nameSearch)) {
		$result = mysqli_query($con,"SELECT * FROM Level WHERE LastName = '$LastName' AND  FirstName = '$FirstName'");
	}

		else {
			$result = mysqli_query($con,"SELECT * FROM Level WHERE Prefix = '$dept' AND Inactive IS NULL");
		}


//admin override levels settings
$chair = "true";
function levelOverride($SNumX, $field) {
$checkID = $SNumX . $field;

echo '<form class="changeCursor" method="post" action="override_dev.php" onClick="this.submit();"><input type="hidden" name="levelOverride" type="text" value="true">
<input type="hidden" name="SNum" type="text" value="'.$SNumX.'">
<input type="hidden" name="field" type="text" value="'.$field.'">
<span style="font-size:1.5em; color:red;" class="glyphicon glyphicon-unchecked" data-toggle="tooltip" data-placement="bottom" title="Override ProfDev"></span></form>';
}
//close admin override levels settings

$CheckImage = '<span style="font-size:1.5em; color:green;" class="glyphicon glyphicon-check"></span>';
$UnCheckImage = '<span style="font-size:1.5em; color:red;" class="glyphicon glyphicon-unchecked"></span>';

// Table Body SQL Select
	while($row = mysqli_fetch_array($result))
	{
		if($row['PendingLevel'] != NULL){
			$pendingLevelCheck = '<span style="font-size:1.0em; color:red; float:right;" class="glyphicon glyphicon-alert" data-toggle="tooltip" data-placement="bottom" title="Pending Level Increase"></span>';
			}
			else {$pendingLevelCheck = " ";}

		$checkID2 = $row['SNum'] . "ID2"; $checkID3 = $row['SNum'] . "ID3"; //set html IDs to update to checked via AJAX if all 3 chair conditions are satisfied --> modalCheckLev2 & modalCheckLev3 on ajax.php
		$EditImage2 = '<form class="changeCursor1"><input type="hidden" name="modalCheckLev2" type="text" value="true"><input type="hidden" name="SNum" type="text" value="'.$row['SNum'].'">
			<span id="'.$checkID2.'" style="font-size:1.5em; color:blue;" class="glyphicon glyphicon-folder-open"></span></form>';
		$EditImage3 = '<form class="changeCursor1"><input type="hidden" name="modalCheckLev3" type="text" value="true"><input type="hidden" name="SNum" type="text" value="'.$row['SNum'].'">
			<span id="'.$checkID3.'" style="font-size:1.5em; color:blue;" class="glyphicon glyphicon-folder-open"></span></form>';
		echo "<tr>";
		echo "<td>";
			echo '<form class="changeCursor1"><input type="hidden" name="SNum" type="text" value="'.$row['SNum'].'">
			<input type="hidden" name="lvl_info_gen" type="text" value="true">
			<img class="changeCursor" src="images/transp-inf.png" /></form>';
		echo "</td>";
		echo "<td style='font-size:1.5em;'>" . $row['SNum'] . "</td>";
		echo '<td >'.$row['LastName'].', '. $row['FirstName'].'</td>';
		echo "<td style='font-size:1.5em;'>";
			echo ''.$row['Level'] .''.$pendingLevelCheck.'';
		echo "</td>";
		echo "<td style='text-align:center;'>"; if($row['D2L'] == 1) {echo $CheckImage;} elseif($chair=="true"){levelOverride($row['SNum'],"D2L");} else {echo $UnCheckImage;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['NFO'] == 1) {echo $CheckImage;} elseif($chair=="true"){levelOverride($row['SNum'],"NFO");} else {echo $UnCheckImage;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['EDU222'] == 1) {echo $CheckImage;} elseif($chair=="true"){levelOverride($row['SNum'],"EDU222");} else {echo $UnCheckImage;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['4SemTeach'] == 1) {echo $CheckImage;} else {echo $UnCheckImage;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['ChairRec2'] == 1) {echo $CheckImage;} else {echo $EditImage2;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['6SemTeach'] == 1) {echo $CheckImage;} else {echo $UnCheckImage;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['15hrProfDev'] == 1) {echo $CheckImage;} else {echo $UnCheckImage;} echo"</td>";
		echo "<td style='text-align:center;'>"; if($row['ChairRec3'] == 1) {echo $CheckImage;} else {echo $EditImage3;} echo"</td>";
		echo "</tr>";
	}


echo' </tbody>';


echo '</table></div>';


?>

<?php
echo '<script>document.getElementById("'.$Display.'").className = "btn btn-primary btn-block active";</script>';
//bootstrap classes added by the uitheme widget
echo '<div id="demo"><table>
<thead>
  <tr>
    <th>SNum</th>
    <th>Name</th>
    <th>Current Level</th>
    <th>Increase To</th>
    <th>VP</th>
    <th>Advance</th>
    </tr>
</thead>
<tfoot>
  <tr>
    <th>SNum</th>
    <th>Name</th>
    <th>Current Level</th>
    <th>Increase To</th>
    <th>VP</th>
    <th>Advance</th>
    </tr>
  <tr>
    <th colspan="6" class="ts-pager form-horizontal">
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

if($_SESSION["role"] == "VP") {
$result = mysqli_query($con,"SELECT * FROM Level WHERE levelIncreaseVP IS NULL AND PendingLevel IS NOT NULL");
}

if($_SESSION["role"] == "payroll") {
$result = mysqli_query($con,"SELECT * FROM Level WHERE levelIncreaseVP IS NOT NULL AND PendingLevel IS NOT NULL");
}

// Table Body SQL Select
while($row = mysqli_fetch_array($result))
{
  //if(!empty($row['Chair'])){$chairCheck="checked";} else{$chairCheck = "";}
  //if(!empty($row['VP'])){$VPCheck="checked";} else{$VPCheck = "";}
  //if(!empty($row['Chairdeny'])){$Chairdeny="checked";} else{$Chairdeny = "";}
  //if(!empty($row['VPdeny'])){$VPdeny="checked";} else{$VPdeny = "";}
  //$ChairDenyID = "ChairDenyID" . $row['id']; $VPdenyID = "VPdenyID" . $row['id']; // set element IDs to check/uncheck boxes based on AJAX response

  echo "<tr>";

  echo "<td>" . $row['SNum'] . "</td>";
  echo '<td>'.$row['LastName'].', '. $row['FirstName'].'</td>';
  echo "<td>" . $row['Level'] . "</td>";
  echo "<td>" . $row['PendingLevel'] . "</td>";

  if($_SESSION["role"] == "VP"){
  echo '<td><form class="form1" style="float:left">A: <input type="checkbox" name="VPlevelIncrease" value="'.$row['SNum'].'"><input type="hidden" name="payrollVP" type="text" value="true"></form> ';
  echo '</td>';}
  else {echo "<td></td>";}

  if($_SESSION["role"] == "payroll"){
  echo '<td><form class="form1" style="float:left">A: <input type="checkbox" name="PayrollLevelIncrease" value="'.$row['SNum'].'">
  <input type="hidden" name="PendingLevel" type="text" value="'.$row['PendingLevel'].'">
  <input type="hidden" name="payrollHR" type="text" value="true"></form>';
  echo '</td>';}
  else {echo "<td></td>";}

  echo "</tr>";
}
// document.getElementById("checkbox").checked = false;



echo' </tbody>';


echo '</table></div>';

?>

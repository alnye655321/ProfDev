<?php
echo '<script>document.getElementById("'.$Display.'").className = "btn btn-primary btn-block active";</script>';
//bootstrap classes added by the uitheme widget
echo '<div id="demo"><table>
<thead>
  <tr>
    <th>Info</th>
    <th>SNum</th>
    <th>Name</th>
    <th>Prefix</th>
    <th>Type</th>
    <!--<th class="filter-select filter-exact" data-placeholder="Pick a gender">Sex</th>-->
    <th>Item</th>
    <th>Date</th>
    <th>Sponsor</th>
    <th>Hours</th>
    <th>Chair</th>
    <th>VP</th>
    <th>Dean</th>
    </tr>
</thead>
<tfoot>
  <tr>
    <th>Info</th>
    <th>SNum</th>
    <th>Name</th>
    <th>Prefix</th>
    <th>Type</th>
    <!--<th>Sex</th>-->
    <th>Item</th>
    <th>Date</th>
    <th>Sponsor</th>
    <th>Hours</th>
    <th>Chair</th>
    <th>VP</th>
    <th>Dean</th>
    </tr>
  <tr>
    <th colspan="13" class="ts-pager form-horizontal">
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
$result = mysqli_query($con,"SELECT * FROM Activity WHERE VP is NULL");
}

else if($_SESSION["role"] == "chair") {
  $result = mysqli_query($con,getDepts($user));
}

  else if (!empty($SNum)){
    $result = mysqli_query($con,"SELECT * FROM Activity WHERE SNum = '$SNum' AND VP is NULL");
  }

    else if(!empty($nameSearch)) {
      $result = mysqli_query($con,"SELECT * FROM Activity WHERE LastName = '$LastName' AND  FirstName = '$FirstName' AND VP is NULL");
    }

      else if($_SESSION["role"] == "dean") {
        $result = mysqli_query($con,"SELECT * FROM Activity WHERE Override = '0'");
      }

        else {
          $result = mysqli_query($con,"SELECT * FROM Activity WHERE Prefix = '$dept' AND VP is NULL");
        }

// Table Body SQL Select
while($row = mysqli_fetch_array($result))
{
  if(!empty($row['Chair'])){$chairCheck="checked";} else{$chairCheck = "";}
  if(!empty($row['VP'])){$VPCheck="checked";} else{$VPCheck = "";}
  if(!empty($row['Chairdeny'])){$Chairdeny="checked";} else{$Chairdeny = "";}
  if(!empty($row['VPdeny'])){$VPdeny="checked";} else{$VPdeny = "";}
  $ChairDenyID = "ChairDenyID" . $row['id']; $VPdenyID = "VPdenyID" . $row['id']; // set element IDs to check/uncheck boxes based on AJAX response

  echo "<tr>";
  echo "<td>";
    echo '<form class="form2"><input type="hidden" name="id1" type="text" value="'.$row['id'].'">
    <input type="hidden" name="InfoCheck" type="text" value="true">
    <img class="changeCursor" src="images/transp-inf.png" /></form>';
  echo "</td>";
  echo "<td>" . $row['SNum'] . "</td>";
  echo '<td>'.$row['LastName'].', '. $row['FirstName'].'</td>';
  echo "<td>" . $row['Prefix'] . "</td>";
  echo "<td>" . $row['Type'] . "</td>";
  echo "<td>" . $row['Item'] . "</td>";
  echo "<td>" . $row['Date'] . "</td>";
  echo "<td>" . $row['Sponsor'] . "</td>";
  echo "<td>" . $row['Hours'] . "</td>";

  if($_SESSION["role"] == "chair"){
  echo '<td><form class="form1" style="float:left">A: <input type="checkbox" '.$chairCheck.' name="Chair" value="'.$row['id'].'"><input type="hidden" name="pendingCheck" type="text" value="true"></form>
    <form class="form1" style="float:right">D: <input type="checkbox" '.$Chairdeny.' name="Chairdeny" id="'.$ChairDenyID.'" value="'.$row['id'].'"><input type="hidden" name="pendingCheck" type="text" value="true"></form>';
  echo '</td>';}
  else {echo "<td></td>";}

  if($_SESSION["role"] == "VP"){
  echo '<td><form class="form1" style="float:left">A: <input type="checkbox" '.$VPCheck.' name="VP" value="'.$row['id'].'"><input type="hidden" name="devType" type="text" value="'.$row['Type'].'"><input type="hidden" name="pendingCheck" type="text" value="true"></form>
    <form class="form1" style="float:right">D: <input type="checkbox" '.$VPdeny.' name="VPdeny" id="'.$VPdenyID.'" value="'.$row['id'].'"><input type="hidden" name="pendingCheck" type="text" value="true"></form>';
  echo '</td>';}
  else {echo "<td></td>";}

  if($_SESSION["role"] == "dean"){
  echo '<td><form class="form1" style="float:left">A: <input type="checkbox" name="Dean" value="'.$row['id'].'"><input type="hidden" name="pendingCheck" type="text" value="true"></form>
    <form class="form1" style="float:right">D: <input type="checkbox" name="deanDeny" value="'.$row['id'].'"><input type="hidden" name="pendingCheck" type="text" value="true"></form>
  </td>';}
  else {echo "<td></td>";}

  echo "</tr>";
}
// document.getElementById("checkbox").checked = false;



echo' </tbody>';


echo '</table></div>';


?>

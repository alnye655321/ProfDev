<?php
echo '<script>document.getElementById("'.$Display.'").className = "btn btn-primary btn-block active";</script>';
//bootstrap classes added by the uitheme widget
echo '<div id="demo"><table>
<thead>
  <tr>
    <th>Info</th>
    <th>SNum</th>
    <th>Name</th>
    <th>Lvl</th>
    <th>Type</th>
    <!--<th class="filter-select filter-exact" data-placeholder="Pick a gender">Sex</th>-->
    <th>Item</th>
    <th>Date</th>
    <th>Sponsor</th>
    <th>Hours</th></tr>
</thead>
<tfoot>
  <tr>
    <th>Info</th>
    <th>SNum</th>
    <th>Name</th>
    <th>Lvl</th>
    <th>Type</th>
    <!--<th>Sex</th>-->
    <th>Item</th>
    <th>Date</th>
    <th>Sponsor</th>
    <th>Hours</th>
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


if (!empty($SNum)){
$result = mysqli_query($con,"SELECT * FROM Activity WHERE SNum = '$SNum' AND VP IS NOT NULL");
}

else if(!empty($nameSearch)) {
  $result = mysqli_query($con,"SELECT * FROM Activity WHERE LastName = '$LastName' AND  FirstName = '$FirstName' AND VP IS NOT NULL");
}

  else {
    $result = mysqli_query($con,"SELECT * FROM Activity WHERE Prefix = '$dept' AND VP IS NOT NULL AND Inactive IS NULL");
  }
//!!!!!!!!!!!!!! Add to type infoajax button <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-info-sign"></span></button>
// Table Body SQL Select
while($row = mysqli_fetch_array($result))
{
  echo "<tr>";
  echo "<td>";
    echo '<form class="form2"><input type="hidden" name="id1" type="text" value="'.$row['id'].'">
    <input type="hidden" name="InfoCheck" type="text" value="true">
    <img class="changeCursor" src="images/transp-inf.png" /></form>';
  echo "</td>";
  echo "<td>" . $row['SNum'] . "</td>";
  echo '<td>'.$row['LastName'].', '. $row['FirstName'].'</td>';
  echo '<td>'; levelNumber($row['SNum']); echo '</td>';
  echo "<td>" . $row['Type'] . "</td>";
  echo "<td>" . $row['Item'] . "</td>";
  echo "<td>" . $row['Date'] . "</td>";
  echo "<td>" . $row['Sponsor'] . "</td>";
  echo "<td>" . $row['Hours'] . "</td>";
  echo "</tr>";
}




echo '</tbody>';


echo '</table></div>';



?>

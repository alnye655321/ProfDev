<?php
session_start();
$con=mysqli_connect("localhost","nyedes5_anye","Pqwoals1inm","nyedes5_ProfDev");
$con2=mysqli_connect("localhost","nyedes5_anye","Pqwoals1inm","nyedes5_schedule");


//$con=mysqli_connect("localhost","Alex","Spring2015",$year);
//$con2=mysqli_connect("localhost","Alex","Spring2015","users");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>

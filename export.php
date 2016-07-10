<?php
include 'id_verify.php'; //$user included as S# from cookie
include 'connect.php';

$Level = $_POST['Level'];
$Activity = $_POST['Activity'];
$Prefix = $_POST['Prefix'];

if($Level == "true")
{
$result = mysqli_query($con, "SHOW COLUMNS FROM Level");
$i = 0;
if (mysqli_num_rows($result) > 0) {
while ($row = mysqli_fetch_assoc($result)) {
$csv_output .= $row['Field'].", ";
$i++;
}
}
$csv_output .= "\n";

	$values = mysqli_query($con, "SELECT * FROM Level");
	while ($rowr = mysqli_fetch_row($values)) {
	for ($j=0;$j<$i;$j++) {
	$csv_output .= $rowr[$j].",";
	}
	$csv_output .= "\n";
	}



$filename = "Level_modified".date("Y-m-d_H-i");
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header( "Content-disposition: filename=".$filename.".csv");
print $csv_output;
exit;
}

if($Activity == "true")
{
$result = mysqli_query($con, "SHOW COLUMNS FROM Activity");
$i = 0;
if (mysqli_num_rows($result) > 0) {
while ($row = mysqli_fetch_assoc($result)) {
$csv_output .= $row['Field'].", ";
$i++;
}
}
$csv_output .= "\n";

	$values = mysqli_query($con, "SELECT * FROM Activity WHERE Prefix = '$Prefix'");
	while ($rowr = mysqli_fetch_row($values)) {
	for ($j=0;$j<$i;$j++) {
	$csv_output .= $rowr[$j].",";
	}
	$csv_output .= "\n";
	}



$filename = "Activity_modified".date("Y-m-d_H-i");
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header( "Content-disposition: filename=".$filename.".csv");
print $csv_output;
exit;
}
?>
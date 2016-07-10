<?php

set_time_limit(0);                   // ignore php timeout
ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly


include 'connect.php';

$Subjects = false;
if($Subjects == true)
{
$result = mysqli_query($con,"SELECT id, SNum FROM Activity");

echo "starting....";

while($row = mysqli_fetch_array($result))
	{
		$SNum = $row['SNum'];
		$id = $row['id'];
		$result1 = mysqli_query($con,"SELECT SNum, Subject FROM TeachingInfo");
		while($row1 = mysqli_fetch_array($result1))
		{
			if($SNum == $row1['SNum'])
			{
				$Subject = $row1['Subject'];
				mysqli_query($con,"UPDATE Activity SET Prefix = '$Subject' WHERE id = '$id'");
				
			}
		
		}
	}
echo "<br>finished";
}

$Names = false;
if($Names == true)
{
$result = mysqli_query($con,"SELECT id, SNum FROM Activity");

echo "starting....";

while($row = mysqli_fetch_array($result))
	{
		$SNum = $row['SNum'];
		$id = $row['id'];
		$result1 = mysqli_query($con,"SELECT SNum, FirstName, LastName FROM TeachingInfo");
		while($row1 = mysqli_fetch_array($result1))
		{
			if($SNum == $row1['SNum'])
			{
				$FirstName = $row1['FirstName'];
				$LastName = $row1['LastName'];
				mysqli_query($con,"UPDATE Activity SET FirstName = '$FirstName' WHERE id = '$id'");
				mysqli_query($con,"UPDATE Activity SET LastName = '$LastName' WHERE id = '$id'");
				
			}
		
		}
	}
echo "<br>finished";
}

$result = mysqli_query($con,"SELECT id, SNum FROM Activity");
$row = mysqli_fetch_array($result);
return $row;
?>
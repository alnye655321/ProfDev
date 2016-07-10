<?php

set_time_limit(0);                   // ignore php timeout
ignore_user_abort(true);             // keep on going even if user pulls the plug*
while(ob_get_level())ob_end_clean(); // remove output buffers
ob_implicit_flush(true);             // output stuff directly


include 'connect.php';


//New function - Add Total Semesters Teaching && Credit/Contact Hours - !!From Old Data Import!!
function newUpdate($SNum){
global $con;

$result = mysqli_query($con,"SELECT Type FROM Activity WHERE Type = 'D2L' AND SNum = '$SNum'");
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0) {mysqli_query($con,"UPDATE Level SET D2L = '1' WHERE SNum = '$SNum'");}

$result = mysqli_query($con,"SELECT Type FROM Activity WHERE Type = 'NFO' AND SNum = '$SNum'");
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0) {mysqli_query($con,"UPDATE Level SET NFO = '1' WHERE SNum = '$SNum'");}

$result = mysqli_query($con,"SELECT Type FROM Activity WHERE Type = 'MPT' AND SNum = '$SNum'");
$row_cnt = mysqli_num_rows($result);
if($row_cnt > 0) {mysqli_query($con,"UPDATE Level SET EDU222 = '1' WHERE SNum = '$SNum'");}


$result = mysqli_query($con,"SELECT DISTINCT Semester FROM TeachingInfo WHERE SNum = '$SNum'"); // get # of semesters taught
	$row_cnt = mysqli_num_rows($result);

$CreditsTotal = 0; $ContactTotal = 0;
	$result1 = mysqli_query($con,"SELECT ContactHours, CourseCreditsHold FROM TeachingInfo WHERE SNum = '$SNum'");	
		while($row = mysqli_fetch_array($result1))
		{
			$ContactTotal = $ContactTotal + $row['ContactHours'];
			$CreditsTotal = $CreditsTotal + $row['CourseCreditsHold'];
		}

if($row_cnt >= 4 && ($ContactTotal >= 180 || $CreditsTotal >= 12) ){
	mysqli_query($con,"UPDATE Level SET 4SemTeach = '1' WHERE SNum = '$SNum'");
}

if($row_cnt >= 6 && ($ContactTotal >= 360 || $CreditsTotal >= 24) ){
	mysqli_query($con,"UPDATE Level SET 6SemTeach = '1' WHERE SNum = '$SNum'");
}

$result2 = mysqli_query($con,"SELECT Level FROM Level WHERE SNum = '$SNum'");
while($row = mysqli_fetch_array($result2)){ //set requirement bools based on old level data
	if($row['Level'] == 2 || $row['Level'] == 3 ) {
		mysqli_query($con,"UPDATE Level SET D2L = '1' WHERE SNum = '$SNum'");
		mysqli_query($con,"UPDATE Level SET NFO = '1' WHERE SNum = '$SNum'");
		mysqli_query($con,"UPDATE Level SET EDU222 = '1' WHERE SNum = '$SNum'");
		mysqli_query($con,"UPDATE Level SET 4SemTeach = '1' WHERE SNum = '$SNum'");
		mysqli_query($con,"UPDATE Level SET ChairRec2 = '1' WHERE SNum = '$SNum'");
		mysqli_query($con,"UPDATE Level SET ChairRec2Obs = '2016-06-28' WHERE SNum = '$SNum'");
		mysqli_query($con,"UPDATE Level SET ChairRec2Eval = '2016-06-28' WHERE SNum = '$SNum'");
		mysqli_query($con,"UPDATE Level SET ChairRec2Ass = '2016-06-28' WHERE SNum = '$SNum'");
	}
	
	if($row['Level'] == 3 ) { //set requirement bools based on old level data
	mysqli_query($con,"UPDATE Level SET 15hrProfDev = '1' WHERE SNum = '$SNum'");
	mysqli_query($con,"UPDATE Level SET 6SemTeach = '1' WHERE SNum = '$SNum'");
	mysqli_query($con,"UPDATE Level SET ChairRec3 = '1' WHERE SNum = '$SNum'");
	mysqli_query($con,"UPDATE Level SET ChairRec3Obs = '2016-06-28' WHERE SNum = '$SNum'");
	mysqli_query($con,"UPDATE Level SET ChairRec3Eval = '2016-06-28' WHERE SNum = '$SNum'");
	mysqli_query($con,"UPDATE Level SET ChairRec3Ass = '2016-06-28' WHERE SNum = '$SNum'");
	}

}


$hoursTotal = 0;
	$result1 = mysqli_query($con,"SELECT Hours FROM Activity WHERE SNum = '$SNum' AND Type != 'D2L' AND Type != 'MPT' AND Type != 'NFO'"); //start counting for 15hr profDev requirement for lvl 3
		while($row = mysqli_fetch_array($result1))
		{
			$hoursTotal = $hoursTotal + $row['Hours'];
		}
		if($hoursTotal >= 15) {
			mysqli_query($con,"UPDATE Level SET 15hrProfDev = '1' WHERE SNum = '$SNum'");
		}

}


//close new function


$Subjects = false; // set prefixes for activity table
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

$prefixes = false; //set prefixes for level table
if($prefixes == true)
{
$result = mysqli_query($con,"SELECT SNum FROM Level");

echo "starting....";

while($row = mysqli_fetch_array($result))
	{
		$SNum = $row['SNum'];
		$result1 = mysqli_query($con,"SELECT SNum, Subject FROM TeachingInfo");
		while($row1 = mysqli_fetch_array($result1))
		{
			if($SNum == $row1['SNum'])
			{
				$Subject = $row1['Subject'];
				mysqli_query($con,"UPDATE Level SET Prefix = '$Subject' WHERE SNum = '$SNum'");
				
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


$levelCheck = false; //set level and requirement info based on old data. All in Level table
if($levelCheck == true)
{
$result3 = mysqli_query($con,"SELECT SNum FROM Level");

echo "starting....";

while($row = mysqli_fetch_array($result3))
	{
		$SNum = $row['SNum'];
		newUpdate($SNum);

	}
echo "<br>finished";
}


$inactiveTransfer = false; // transfer inactive boolean to activity table for all S#s
if($inactiveTransfer == true)
{
$result3 = mysqli_query($con,"SELECT SNum, Inactive FROM Level");

echo "starting....";

while($row = mysqli_fetch_array($result3))
	{
		$SNum = $row['SNum'];
		$inactive = $row['Inactive'];
		
		if($row['Inactive'] == 1){
			$result4 = mysqli_query($con,"SELECT SNum FROM Activity");
			while($row = mysqli_fetch_array($result4))
			{
				$SNumX = $row['SNum'];
				if($SNum == $SNumX ){
					mysqli_query($con,"UPDATE Activity SET Inactive = '$inactive' WHERE SNum = '$SNumX'");
				
				}
			
			}
		}

	}
echo "<br>finished";
}


//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!Finish Script!!!!!!!!!!!!!!!!!!!!! Add Levels table entries to new faculty, based on new semester .csv data import
$levelsTableAdd = false;
if($levelsTableAdd == true)
{
$Level = 1;

$sql2 = "INSERT INTO Level (SNum, FirstName, LastName, Prefix, Level)
VALUES ('$SNum','$FirstName','$LastName','$Prefix','$Level')";

if (mysqli_query($con, $sql2)){

	echo "New record created successfully";}
	else {
    echo "Error: " . $sql2 . "<br>" . mysqli_error($con);
}

}

//$result = mysqli_query($con,"SELECT id, SNum FROM Activity");
//$row = mysqli_fetch_array($result);
//return $row;
?>
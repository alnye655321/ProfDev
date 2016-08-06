<?php

/*
if($login == true)
	{		$count = $row['Logins'] + 1;
			mysqli_query($con2,"UPDATE Users SET Logins = '$count' WHERE SNum = '$user'");	
			$hour = time() + 14400;
			setcookie("ID_Data", $user, $hour);
			setcookie("Key_Data", $pass, $hour);	
			echo "<script> window.location.assign('index.php'); </script>";}

*/
if(isset($_COOKIE["ID_Data"])) 
{
$user = $_COOKIE['ID_Data']; 
}





  if(isset($_COOKIE['ID_Data']))
			$hour = time() - 14400;
			setcookie("ID_Data", $user, $hour);

echo '<p>You have logged out</p>';




?>
<?php

include 'connect.php';



$InfoCheck = @$_POST['InfoCheck'];
$modalCheck = @$_POST['modalCheck'];
$modalSubmit = @$_POST['modalSubmit'];


// Info AJAX
if($InfoCheck == "true")
{

class ajaxValidate {
function formValidate() {

global $con; global $id1;


 //Put form elements into post variables (this is where you would sanitize your data)
$id1 = @$_POST['id1'];

$result = mysqli_query($con,"SELECT * FROM Activity WHERE id = '$id1'");

                
//Establish values that will be returned via ajax
$return = array();
$return['msg'] = '';
$return['type'] = '';
 
$return['error'] = false;

	//Additional Info
		$return['type'] = 'Info';
		
while($row = mysqli_fetch_array($result)) {
//Return json encoded results
	$return['msg'] = '<ul>
	<li>LastName: ' . $row['LastName'] . '</li>
	<li>FirstName: ' . $row['FirstName'] . '</li>
	<li>SNum: ' . $row['SNum'] . '</li>
	<li>Type: ' . $row['Type'] . '</li>
	<li>Item: ' . $row['Item'] . '</li>
	<li>Date: ' . $row['Date'] . '</li>
	<li>Sponsor: ' . $row['Sponsor'] . '</li>
	<li>Hours: ' . $row['Hours'] . '</li>
	<li>Chair Approval: ' . $row['Chair'] . '</li>
	<li>VP Approval: ' . $row['VP'] . '</li>
	<li>Payroll Approval: ' . $row['Payroll'] . '</li>
   <li>Comments: ' . $row['Comments'] . '</li></ul><br><input type="button" name="answer" value="Close" onclick="hideDiv()" />';
}
	return json_encode($return);

//Close Additional Info Form





  }
}
$ajaxValidate = new ajaxValidate;
echo $ajaxValidate->formValidate();
}
// Close Info AJAX



// Modal AJAX Generate form
if($modalCheck == "true")
{
	

class ajaxValidate {
function formValidate() {

global $con; global $id1;
$SNum = @$_POST['SNum'];

 //Put form elements into post variables (this is where you would sanitize your data)
$id1 = @$_POST['id1'];

$result = mysqli_query($con,"SELECT * FROM Activity WHERE id = '$id1'");

                
//Establish values that will be returned via ajax
$return = array();
$return['msg'] = '';
$return['type'] = '';
 
$return['error'] = false;

	//Additional Info
		$return['type'] = 'Info';
		

//Return json encoded results
	$return['msg'] = '<input type="hidden" name="SNum" type="text" value="'.$SNum.'">';


	return json_encode($return);

//Close Modal AJAX Generate form


  }
}
$ajaxValidate = new ajaxValidate;
echo $ajaxValidate->formValidate();
}


// Modal AJAX Submit Form
if($modalSubmit == "true")
{
$SNum = @$_POST['SNum'];
$satTeach = @$_POST['satTeach'];
$courseEval = @$_POST['courseEval'];
$assReq = @$_POST['assReq'];

class ajaxValidate {
function formValidate() {

global $con;


                
//Establish values that will be returned via ajax
$return = array();
$return['msg'] = '';
$return['type'] = '';
 
$return['error'] = false;

	//Additional Info
		$return['type'] = '';

$today = date("Y/m/d");	

if($satTeach == "true") {mysqli_query($con,"UPDATE Level SET ChairRec2Obs = '$today' WHERE SNum = '$SNum'");}
if($courseEval == "true") {mysqli_query($con,"UPDATE Level SET ChairRec2Eval = '$today' WHERE SNum = '$SNum'");}
if($assReq == "true") {mysqli_query($con,"UPDATE Level SET 	ChairRec2Ass = '$today' WHERE SNum = '$SNum'");}


	return json_encode($return);

//Close Modal AJAX Submit Form


  }
}
$ajaxValidate = new ajaxValidate;
echo $ajaxValidate->formValidate();
}


?>
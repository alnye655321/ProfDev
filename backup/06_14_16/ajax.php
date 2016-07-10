<?php

include 'connect.php';


$pendingCheck = @$_POST['pendingCheck'];
$InfoCheck = @$_POST['InfoCheck'];
$modalCheckLev2 = @$_POST['modalCheckLev2'];
$modalCheckLev3 = @$_POST['modalCheckLev3'];
$modalSubmit = @$_POST['modalSubmit'];

// Pending Activity Submit/Deny
if($pendingCheck == "true")
{
class ajaxValidate {
function formValidate() {

global $con; global $id; 


 //Put form elements into post variables (this is where you would sanitize your data)
$Chair = @$_POST['Chair']; 
$VP = @$_POST['VP']; 

$today = date("Y/m/d");

if(!empty($Chair)){
	$id = @$_POST['Chair'];
	mysqli_query($con,"UPDATE Activity SET Chair = '$today' WHERE id = '$id'");
	}

if(!empty($VP)){
	$id = @$_POST['VP'];
	mysqli_query($con,"UPDATE Activity SET VP = '$today' WHERE id = '$id'");
	}


                
//Establish values that will be returned via ajax
$return = array();
$return['msg'] = '';
$return['type'] = '';
 
$return['error'] = false;

$return['type'] = 'Sent';







  }
}
$ajaxValidate = new ajaxValidate;
echo $ajaxValidate->formValidate();
}
// Close Pending Activity Submit/Deny

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







  }
}
$ajaxValidate = new ajaxValidate;
echo $ajaxValidate->formValidate();
}
// Close Info AJAX



// Modal AJAX Generate form
if($modalCheckLev2 == "true" || $modalCheckLev3 == "true")
{
	

class ajaxValidate {
function formValidate() {

global $con;
$SNum = @$_POST['SNum'];

 //Put form elements into post variables (this is where you would sanitize your data)
$modalCheckLev2 = @$_POST['modalCheckLev2'];
$modalCheckLev3 = @$_POST['modalCheckLev3'];

                
//Establish values that will be returned via ajax
$return = array();
$return['msg'] = '';
$return['type'] = '';
 
$return['error'] = false;

	//Additional Info
		$return['type'] = 'Info';



$result = mysqli_query($con,"SELECT * FROM Level WHERE SNum = '$SNum'"); //check if all chair rec values are present
while($row = mysqli_fetch_array($result)) {
if($row['ChairRec2Obs'] != NULL){$checked_ChairRec2Obs = "checked";} else{$checked_ChairRec2Obs = " ";}
if($row['ChairRec2Eval'] != NULL){$checked_ChairRec2Eval = "checked";} else{$checked_ChairRec2Eval = " ";}
if($row['ChairRec2Ass'] != NULL){$checked_ChairRec2Ass = "checked";} else{$checked_ChairRec2Ass = " ";}
if($row['ChairRec3Obs'] != NULL){$checked_ChairRec3Obs = "checked";} else{$checked_ChairRec3Obs = " ";}
if($row['ChairRec3Eval'] != NULL){$checked_ChairRec3Eval = "checked";} else{$checked_ChairRec3Eval = " ";}
if($row['ChairRec3Ass'] != NULL){$checked_ChairRec3Ass = "checked";} else{$checked_ChairRec3Ass = " ";}
}

//Return json encoded results
if($modalCheckLev2 == "true") {
	$return['msg'] = '<div class="checkbox"><label><input type="checkbox" value="true" name="satTeach2"'.$checked_ChairRec2Obs.'>Satisfactory Teaching Observation</label></div>
	<div class="checkbox"><label><input type="checkbox" value="true" name="courseEval2"'.$checked_ChairRec2Eval.'>Completion of Course Eval Every Semester</label></div>
	<div class="checkbox"><label><input type="checkbox" value="true" name="assReq2"'.$checked_ChairRec2Ass.'>Completed Assessment Requirements Every Semester</label></div>
	<input type="hidden" name="SNum" type="text" value="'.$SNum.'">';
}

if($modalCheckLev3 == "true") {
	$return['msg'] = '<div class="checkbox"><label><input type="checkbox" value="true" name="satTeach3"'.$checked_ChairRec3Obs.'>Satisfactory Teaching Observation</label></div>
	<div class="checkbox"><label><input type="checkbox" value="true" name="courseEval3"'.$checked_ChairRec3Eval.'>Completion of Course Eval Every Semester</label></div>
	<div class="checkbox"><label><input type="checkbox" value="true" name="assReq3"'.$checked_ChairRec3Ass.'>Completed Assessment Requirements Every Semester</label></div>
	<input type="hidden" name="SNum" type="text" value="'.$SNum.'">';
}

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


class ajaxValidate {
function formValidate() {

global $con;
$SNum = @$_POST['SNum'];

$satTeach2 = @$_POST['satTeach2'];
$courseEval2 = @$_POST['courseEval2'];
$assReq2 = @$_POST['assReq2'];

$satTeach3 = @$_POST['satTeach3'];
$courseEval3 = @$_POST['courseEval3'];
$assReq3 = @$_POST['assReq3'];

                
//Establish values that will be returned via ajax
$return = array();
$return['msg'] = '';
$return['type'] = '';
$return['SNumID'] = '';
 
$return['error'] = false;

	//Additional Info
		$return['type'] = '';

$today = date("Y/m/d");	

if($satTeach2 == "true") {mysqli_query($con,"UPDATE Level SET ChairRec2Obs = '$today' WHERE SNum = '$SNum'");}
if($courseEval2 == "true") {mysqli_query($con,"UPDATE Level SET ChairRec2Eval = '$today' WHERE SNum = '$SNum'");}
if($assReq2 == "true") {mysqli_query($con,"UPDATE Level SET 	ChairRec2Ass = '$today' WHERE SNum = '$SNum'");}

if($satTeach3 == "true") {mysqli_query($con,"UPDATE Level SET ChairRec3Obs = '$today' WHERE SNum = '$SNum'");}
if($courseEval3 == "true") {mysqli_query($con,"UPDATE Level SET ChairRec3Eval = '$today' WHERE SNum = '$SNum'");}
if($assReq3 == "true") {mysqli_query($con,"UPDATE Level SET 	ChairRec3Ass = '$today' WHERE SNum = '$SNum'");}

$result = mysqli_query($con,"SELECT * FROM Level WHERE SNum = '$SNum'"); //check if all chair rec values are present
while($row = mysqli_fetch_array($result)) {
	

	if($row['ChairRec2Obs'] != NULL && $row['ChairRec2Eval'] != NULL && $row['ChairRec2Ass'] != NULL) {
		mysqli_query($con,"UPDATE Level SET ChairRec2 = '1' WHERE SNum = '$SNum'"); //then update overall ChairRec value based on level
		$return['type'] = 'ChairRec2Complete';
		$return['SNumID'] = $SNum . "ID2";
	}
	
	if($row['ChairRec3Obs'] != NULL && $row['ChairRec3Eval'] != NULL && $row['ChairRec3Ass'] != NULL) {
		mysqli_query($con,"UPDATE Level SET ChairRec3 = '1' WHERE SNum = '$SNum'"); //then update overall ChairRec value based on level
		$return['type'] = 'ChairRec3Complete';
		$return['SNumID'] = $SNum . "ID3";
	}
	
}

	return json_encode($return);




  }
}
$ajaxValidate = new ajaxValidate;
echo $ajaxValidate->formValidate();
}
//Close Modal AJAX Submit Form


?>
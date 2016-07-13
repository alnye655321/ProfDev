<?php

include 'connect.php';

	
//Level Set info 
//Running each time an approval/denial is made

// SemCalc($SNum, 2);
// Add Total Semesters Teaching && Credit/Contact Hours
function levelUpdate($SNum)
{
	global $con;
	
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM Level WHERE SNum = '$SNum'"));
	$EDU222 = $getID["EDU222"]; $D2L = $getID["D2L"]; $NFO = $getID["NFO"]; $level = $getID["Level"];
	$ChairRec2 = $getID["ChairRec2"]; $ChairRec3 = $getID["ChairRec3"]; $LastName = $getID["LastName"];
	$FirstName = $getID["FirstName"]; $Prefix = $getID["Prefix"];
	
	$Type = "PendingLevel";
	
	$result = mysqli_query($con,"SELECT DISTINCT Semester FROM TeachingInfo WHERE SNum = '$SNum'"); // get # of semesters taught
	$row_cnt = mysqli_num_rows($result);	
	
	$CreditsTotal = 0; $ContactTotal = 0; //Calculate total contact hours and credit hours from COGNOS import TeachingInfo
	$result1 = mysqli_query($con,"SELECT ContactHours, CourseCreditsHold FROM TeachingInfo WHERE SNum = '$SNum'");	
		while($row = mysqli_fetch_array($result1))
		{
			$ContactTotal = $ContactTotal + $row['ContactHours'];
			$CreditsTotal = $CreditsTotal + $row['CourseCreditsHold'];
		}
	
	$hoursTotal = 0; //start counting for 15hr profDev requirement for lvl 3
	$result1 = mysqli_query($con,"SELECT Hours FROM Activity WHERE SNum = '$SNum' AND Type != 'D2L' AND Type != 'MPT' AND Type != 'NFO'"); 
		while($row = mysqli_fetch_array($result1))
		{
			$hoursTotal = $hoursTotal + $row['Hours'];
		}
		if($hoursTotal >= 15) {
			mysqli_query($con,"UPDATE Level SET 15hrProfDev = '1' WHERE SNum = '$SNum'");
		}
	
	//Move to Level 2 = 4 semesters && 12 credit hours or 180 contact hours
	if($level == 1){
		if($row_cnt >= 4 && ($ContactTotal >= 180 || $CreditsTotal >= 12) ){
			mysqli_query($con,"UPDATE Level SET 4SemTeach = '1' WHERE SNum = '$SNum'");
			
			if($EDU222 == 1 && $D2L == 1 && $NFO == 1 && $ChairRec2 == 1) {
				mysqli_query($con,"UPDATE Level SET PendingLevel = '2' WHERE SNum = '$SNum'"); //update level display to show pending level change alert
				

				
			}
		}
	}
	//Move to Level 3 = 6 semesters && 24 credit hours or 360 contact hours
	if($level == 2){
		if($row_cnt >= 6 && ($ContactTotal >= 360 || $CreditsTotal >= 24 && $hoursTotal >= 15) ){
			mysqli_query($con,"UPDATE Level SET 6SemTeach = '1' WHERE SNum = '$SNum'");
			
			if($ChairRec3 == 1) {
				mysqli_query($con,"UPDATE Level SET PendingLevel = '3' WHERE SNum = '$SNum'"); //update level display to show pending level change alert
				

			}		
		}
	}
		
}
//Close Level Set info

function getChairName($SNum) {
	global $con;
	global $con2;
	
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT DISTINCT Subject FROM TeachingInfo WHERE SNum = '$SNum' LIMIT 1"));
	$Prefix = $getID["Subject"];	
		
	$result = mysqli_query($con2,"SELECT Depts, Name FROM Users WHERE Chair = '1'");
	
	while($row = mysqli_fetch_array($result))	{
		$prefixList = $row['Depts']; $Name = $row['Name'];
		
		$prefixSplit=explode(" ",$prefixList);
	   foreach($prefixSplit as $value) {
	   	if($value == $Prefix) {
	   		return $Name;
	   	}
		}
	}
}


//email function	
	function email($to, $cc, $MailSubject, $message){
	
		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		//$message = wordwrap($message, 70, "\r\n");					
		$headers = "From: CCA_ProfDev" . "\r\n" .
		"CC: $cc";
		// Send
		mail($to, $MailSubject, $message,$headers);	
	}

function activeStatus($SNum) {
	global $con;
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT Inactive FROM Level WHERE SNum = '$SNum'"));
	$Inactive = $getID["Inactive"];
	if($Inactive == 1) {
		return "Inactive";
	}
	else{
		return "Active";
	}
}


//set posted variables
$pendingCheck = @$_POST['pendingCheck'];
$InfoCheck = @$_POST['InfoCheck'];
$modalCheckLev2 = @$_POST['modalCheckLev2'];
$modalCheckLev3 = @$_POST['modalCheckLev3'];
$modalSubmit = @$_POST['modalSubmit'];
$levelOverride = @$_POST['levelOverride'];
$payrollVP = @$_POST['payrollVP'];
$payrollHR = @$_POST['payrollHR'];
$lvl_info_gen = @$_POST['lvl_info_gen'];
$lvl_info_submit = @$_POST['lvl_info_submit'];



// Pending Activity Submit/Deny
if($pendingCheck == "true")
{
class ajaxValidate {
function formValidate() {

global $con; global $id; global $con2;


 //Put form elements into post variables (this is where you would sanitize your data)
$Chair = @$_POST['Chair'];
$VP = @$_POST['VP'];
$Dean = @$_POST['Dean'];
$Chairdeny = @$_POST['Chairdeny'];
$VPdeny = @$_POST['VPdeny'];
$deanDeny = @$_POST['deanDeny'];

//Establish values that will be returned via ajax
$return = array();
$return['msg'] = '';
$return['type'] = ''; 
$return['error'] = false;
$return['type'] = '';

$today = date("Y/m/d");

//$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT SNum FROM Activity WHERE id = '$id'"));
//$SNum = $getID["SNum"];

// lots of email here --->>>>>>>
if(!empty($Chair)){
	$id = @$_POST['Chair'];
	mysqli_query($con,"UPDATE Activity SET Chair = '$today' WHERE id = '$id'");
	mysqli_query($con,"UPDATE Activity SET Chairdeny = NULL WHERE id = '$id'");
	$return['type'] = "ChairdenyRemove";
	$return['msg'] = "ChairDenyID" . $id;
	
	//email
	$getID = mysqli_fetch_assoc(mysqli_query($con2,"SELECT Email FROM Users WHERE VP = '1' LIMIT 1"));
	$to = $getID["Email"];
	$MailSubject = "ProDev - New Activity for VP Approval";
	$message = "ProDev - New Activity for VP Approval";
	$headers = "From: CCA_ProDev" . "\r\n" .
	"CC: alnye655321@gmail.com";
	mail($to, $MailSubject, $message,$headers);	
	//close email
	}

if(!empty($VP)){
	$id = @$_POST['VP'];
	$devType = @$_POST['devType'];
	
	mysqli_query($con,"UPDATE Activity SET VP = '$today' WHERE id = '$id'");
	mysqli_query($con,"UPDATE Activity SET VPdeny = NULL WHERE id = '$id'");
	$return['type'] = "VPdenyRemove";
	$return['msg'] = "VPdenyID" . $id;
	
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT SNum FROM Activity WHERE id = '$id'"));
	$SNum = $getID["SNum"];
	
	if($devType == "D2L" || $devType == "MPT" || $devType == "NFO") {  //set Level table booleans if one of these types
		if($devType == "MPT"){$devType = "EDU222";} // if MPT type change to EDU222 for column search) {}		
		mysqli_query($con,"UPDATE Level SET $devType = '1' WHERE SNum = '$SNum'");
	}
	
	levelUpdate($SNum); //run level update based on fetched SNum
	}
	
if(!empty($Dean)){
	$id = @$_POST['Dean'];
	mysqli_query($con,"UPDATE Activity SET Override = '1' WHERE id = '$id'"); // Override settings: 0 = proposed by chair; 1 = approved by dean
	
	$getID = mysqli_fetch_assoc(mysqli_query($con,"SELECT SNum FROM Activity WHERE id = '$id'"));
	$SNum = $getID["SNum"];
	levelUpdate($SNum); //run level update based on fetched SNum
	}

if(!empty($Chairdeny)) {
	//add column to activity table, deny, set deny to true & email to submitter
	$id = @$_POST['Chairdeny'];
	mysqli_query($con,"UPDATE Activity SET Chairdeny = '$today' WHERE id = '$id'");
}

if(!empty($VPdeny)) {
	//add column to activity table, deny, set deny to true & clear chair date $ email to chair/submitter
	$id = @$_POST['VPdeny'];
	mysqli_query($con,"UPDATE Activity SET VPdeny = '$today' WHERE id = '$id'");
}
                
return json_encode($return);



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
$return['activityID'] = '<input type="hidden" name="activityID" type="text" value="'.$id1.'">';

		
while($row = mysqli_fetch_array($result)) {
	$chairName = getChairName($row['SNum']);
	$active = activeStatus($row['SNum']);
//Return json encoded results
	$return['msg'] = '<dl class="dl-horizontal">
	<dt>LastName</dt><dd> ' . $row['LastName'] . '</dd>
	<dt>FirstName</dt><dd> ' . $row['FirstName'] . '</dd>
	<dt>SNum</dt><dd> ' . $row['SNum'] . '</dd>
	<dt>Prefix</dt><dd> ' . $row['Prefix'] . '</dd>
	<dt>Status</dt><dd> ' . $active . '</dd>
	<dt>Type</dt><dd> ' . $row['Type'] . '</dd>
	<dt>Item</dt><dd> ' . $row['Item'] . '</dd>
	<dt>Date</dt><dd> ' . $row['Date'] . '</dd>
	<dt>Sponsor</dt><dd> ' . $row['Sponsor'] . '</dd>
	<dt>Hours</dt><dd> ' . $row['Hours'] . '</dd>
	<dt>Chair</dt><dd> ' . $chairName . '</dd>
	<dt>Chair Approval</dt><dd> ' . $row['Chair'] . '</dd>
	<dt>VP Approval</dt><dd> ' . $row['VP'] . '</dd>
	<dt>File</dt><dd>  <a href="'.$row['File'].'" target="_blank">'.$row['File'].'</a> </dd>
   <dt>Comments</dt><dd> ' . $row['Comments'] . '</dd></dl>';
}
	return json_encode($return);







  }
}
$ajaxValidate = new ajaxValidate;
echo $ajaxValidate->formValidate();
}
// Close Info AJAX



// Modal AJAX Generate ChairRec form
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


// Modal AJAX Submit Chiar Rec Form
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
levelUpdate($SNum); //run level update based on fetched SNum

	return json_encode($return);




  }
}
$ajaxValidate = new ajaxValidate;
echo $ajaxValidate->formValidate();
}
//Close Modal AJAX Submit Form


// Payroll Submit
// from class="form1"
if($payrollVP == "true" || $payrollHR == "true")
{


class ajaxValidate {
function formValidate() {

global $con; global $payrollVP; global $payrollHR;

if($payrollVP == "true") {
	$SNum = @$_POST['VPlevelIncrease'];
	mysqli_query($con,"UPDATE Level SET levelIncreaseVP = '1' WHERE SNum = '$SNum'");
	
}


if($payrollHR == "true") {
	$SNum = @$_POST['PayrollLevelIncrease'];
	$PendingLevel = @$_POST['PendingLevel'];
	
	mysqli_query($con,"UPDATE Level SET Level = '$PendingLevel' WHERE SNum = '$SNum'");
	mysqli_query($con,"UPDATE Level SET PendingLevel = NULL WHERE SNum = '$SNum'");
	mysqli_query($con,"UPDATE Level SET levelIncreaseVP = NULL WHERE SNum = '$SNum'");
	
}





                
//Establish values that will be returned via ajax
$return = array();
$return['msg'] = '';
$return['type'] = '';
$return['SNumID'] = '';
 
$return['error'] = false;

	//Additional Info
		$return['type'] = '';



	return json_encode($return);




  }
}
$ajaxValidate = new ajaxValidate;
echo $ajaxValidate->formValidate();
}
//Close Payroll Submit


// Modal AJAX Generate Level Info - change (in)active
if($lvl_info_gen == "true")
{

class ajaxValidate {
function formValidate() {

global $con;
$SNum = @$_POST['SNum'];

              
//Establish values that will be returned via ajax
$return = array();
$return['msg'] = '';

 
$return['error'] = false;

	//Additional Info
		$return['type'] = 'lvl_info';



$result = mysqli_query($con,"SELECT Inactive FROM Level WHERE SNum = '$SNum'"); //check if all chair rec values are present
while($row = mysqli_fetch_array($result)) {
$inactiveCheck = $row['Inactive'];
}

if($inactiveCheck == 1) {
	$checkedCheck = " checked";
}
else {
	$checkedCheck = " ";
}

//Return json encoded results

	$return['msg'] = '<div class="checkbox"><label><input type="checkbox" value="true" name="Inactive"'.$checkedCheck.'>Inactive</label></div>
	<input type="hidden" name="SNum" type="text" value="'.$SNum.'">';



	return json_encode($return);




  }
}
$ajaxValidate = new ajaxValidate;
echo $ajaxValidate->formValidate();
}
//Close Modal AJAX Generate Level Info - change (in)active


// Modal AJAX Submit Level Info - change (in)active Form
if($lvl_info_submit == "true")
{

class ajaxValidate {
function formValidate() {

global $con;
$SNum = @$_POST['SNum'];
$Inactive = @$_POST['Inactive'];
                
//Establish values that will be returned via ajax
$return = array();
$return['msg'] = '';
$return['type'] = '';
$return['SNumID'] = '';
 
$return['error'] = false;

if($Inactive == "true") {mysqli_query($con,"UPDATE Level SET Inactive = '1' WHERE SNum = '$SNum'");}
else{mysqli_query($con,"UPDATE Level SET Inactive = NULL WHERE SNum = '$SNum'");}

	return json_encode($return);

  }
}
$ajaxValidate = new ajaxValidate;
echo $ajaxValidate->formValidate();
}
//Close Modal AJAX Submit Level Info - change (in)active Form


?>
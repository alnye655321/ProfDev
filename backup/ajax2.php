<?php
include 'connect.php';
$modalSubmit = @$_POST['modalSubmit'];
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
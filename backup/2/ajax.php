<?php
include 'connect.php';


class ajaxValidate {
function formValidate() {

global $con; global $id; 


 //Put form elements into post variables (this is where you would sanitize your data)
$Chair = @$_POST['Chair']; 
$VP = @$_POST['VP']; 
$Payroll = @$_POST['Payroll']; 
$today = date("Y/m/d");

if(!empty($Chair)){
	$id = @$_POST['Chair'];
	mysqli_query($con,"UPDATE Activity SET Chair = '$today' WHERE id = '$id'");
	}

if(!empty($VP)){
	$id = @$_POST['VP'];
	mysqli_query($con,"UPDATE Activity SET VP = '$today' WHERE id = '$id'");
	}

if(!empty($Payroll)){
	$id = @$_POST['Payroll'];
	mysqli_query($con,"UPDATE Activity SET Payroll = '$today' WHERE id = '$id'");
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
// Close Select AJAX



?>
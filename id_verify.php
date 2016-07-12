<?php
if(isset($_COOKIE["ID_Data"])) 
{
	$user = $_COOKIE['ID_Data'];
//email function	
	function email($to, $cc, $MailSubject, $message){
	
		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");					
		$headers = "From: CCA_ProfDev" . "\r\n" .
		"CC: $cc";
		// Send
		mail($to, $MailSubject, $message,$headers);	
	}

}
else {echo "<script> window.location.assign('login.php'); </script>"; exit();}
?>
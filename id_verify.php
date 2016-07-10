<?php
if(isset($_COOKIE["ID_Data"])) 
{
$user = $_COOKIE['ID_Data'];
}
else {echo "<script> window.location.assign('login.php'); </script>"; exit();}
?>
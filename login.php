<?php
//turn on !!!!!!!!!!!!!!!!! for CCA Server!!!!!!!!!!!!!!!!!!
/*if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}*/



//include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$user = strtoupper($_POST['user']);
	$pass = $_POST['pass'];
	$year = $_POST['year'];
	$pass = md5($pass);
	$login = true;
include 'connect.php';		
	$result = mysqli_query($con2,"SELECT * FROM Users WHERE SNum = '$user'");
	if(mysqli_num_rows($result)==0)
	{echo '<html><br><body style="color: #FF0004"><div style="text-align:center"><strong>S# not found in database. Please re-enter. </strong></div></body></html>'; $login=false;}
	
	while($row = mysqli_fetch_array($result)) 
		{  
			if($pass != $row['Pass'])
			{echo '<html><br><body style="color: #FF0004"><div style="text-align:center"><strong>Password is incorrect. Please re-enter.</strong></div></body></html>'; $login=false;}
			
			if($row['Logins'] == 0 && $pass == $row['Pass'])
			{$login=false; echo "<script> window.location.assign('changepw.php'); </script>";  }

		
		}
		
	if($login == true && $year == "ProfDev")
	{		$count = $row['Logins'] + 1;
			mysqli_query($con2,"UPDATE Users SET Logins = '$count' WHERE SNum = '$user'");	
			$hour = time() + 14400;
			setcookie("ID_Data", $user, $hour);
			//setcookie("Key_Data", $pass, $hour);	
			echo "<script> window.location.assign('index.php'); </script>";}		
		
		
		
	elseif($login == true)
	{		$count = $row['Logins'] + 1;
			mysqli_query($con2,"UPDATE Users SET Logins = '$count' WHERE SNum = '$user'");	
			$hour = time() + 14400;
			$user = $user . "_" . $year;
			setcookie("ID_Data", $user, $hour);
			//setcookie("Key_Data", $pass, $hour);	
			echo "<script> window.location.assign('index.php'); </script>";}

}


mysqli_close($con2);
?> 
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<title>CCA Annual Scheduling</title>
<head>
<style>
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed,
figure, figcaption, footer, header, hgroup,
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
  margin: 0;
  padding: 0;
  border: 0;
  font-size: 100%;
  font: inherit;
  vertical-align: baseline;
}

article, aside, details, figcaption, figure,
footer, header, hgroup, menu, nav, section {
  display: block;
}

body {
  line-height: 1;
}

ol, ul {
  list-style: none;
}

blockquote, q {
  quotes: none;
}

blockquote:before, blockquote:after,
q:before, q:after {
  content: '';
  content: none;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
}

.about {
  margin: 70px auto 40px;
  padding: 8px;
  width: 260px;
  font: 10px/18px 'Lucida Grande', Arial, sans-serif;
  color: #666;
  text-align: center;
  text-shadow: 0 1px rgba(255, 255, 255, 0.25);
  background: #eee;
  background: rgba(250, 250, 250, 0.8);
  border-radius: 4px;
  background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.1));
  background-image: -moz-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.1));
  background-image: -o-linear-gradient(top, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.1));
  background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.1));
  -webkit-box-shadow: inset 0 1px rgba(255, 255, 255, 0.3), inset 0 0 0 1px rgba(255, 255, 255, 0.1), 0 0 6px rgba(0, 0, 0, 0.2);
  box-shadow: inset 0 1px rgba(255, 255, 255, 0.3), inset 0 0 0 1px rgba(255, 255, 255, 0.1), 0 0 6px rgba(0, 0, 0, 0.2);
}
.about a {
  color: #333;
  text-decoration: none;
  border-radius: 2px;
  -webkit-transition: background 0.1s;
  -moz-transition: background 0.1s;
  -o-transition: background 0.1s;
  transition: background 0.1s;
}
.about a:hover {
  text-decoration: none;
  background: #fafafa;
  background: rgba(255, 255, 255, 0.7);
}

.about-links {
  height: 30px;
}
.about-links > a {
  float: left;
  width: 50%;
  line-height: 30px;
  font-size: 12px;
}

.about-author {
  margin-top: 5px;
}
.about-author > a {
  padding: 1px 3px;
  margin: 0 -1px;
}


body, .login-submit, .login-submit:before, .login-submit:after {
  background: #373737 url("../img/bg.png") 0 0 repeat;
}

body {
  font: 14px/20px 'Helvetica Neue', Helvetica, Arial, sans-serif;
  color: #404040;
}

a {
  color: #00a1d2;
  text-decoration: none;
}
a:hover {
  text-decoration: underline;
}

.login {
  position: relative;
  margin: 80px auto;
  width: 400px;
  padding-right: 32px;
  font-weight: 300;
  color: #a8a7a8;
  text-shadow: 1px 1px 0 rgba(0, 0, 0, 0.8);
}
.login p {
  margin: 0 0 10px;
}

input, button, label {
  font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
  font-size: 15px;
  font-weight: 300;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}

input[type=text], input[type=password] {
  padding: 0 10px;
  width: 300px;
  height: 40px;
  color: #bbb;
  text-shadow: 1px 1px 1px black;
  background: rgba(0, 0, 0, 0.16);
  border: 0;
  border-radius: 5px;
  -webkit-box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.06);
  box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.06);
}
input[type=text]:focus, input[type=password]:focus {
  color: white;
  background: rgba(0, 0, 0, 0.1);
  outline: 0;
}

label {
  float: left;
  width: 100px;
  line-height: 40px;
  padding-right: 10px;
  font-weight: 100;
  text-align: right;
  letter-spacing: 1px;
}

.forgot-password {
  padding-left: 100px;
  font-size: 13px;
  font-weight: 100;
  letter-spacing: 1px;
}

.login-submit {
  position: absolute;
  top: 12px;
  right: 0;
  width: 48px;
  height: 48px;
  padding: 8px;
  border-radius: 32px;
  -webkit-box-shadow: 0 0 4px rgba(0, 0, 0, 0.35);
  box-shadow: 0 0 4px rgba(0, 0, 0, 0.35);
}
.login-submit:before, .login-submit:after {
  content: '';
  z-index: 1;
  position: absolute;
}
.login-submit:before {
  top: 28px;
  left: -4px;
  width: 4px;
  height: 10px;
  -webkit-box-shadow: inset 0 1px rgba(255, 255, 255, 0.06);
  box-shadow: inset 0 1px rgba(255, 255, 255, 0.06);
}
.login-submit:after {
  top: -4px;
  bottom: -4px;
  right: -4px;
  width: 36px;
}

.login-button {
  position: relative;
  z-index: 2;
  width: 48px;
  height: 48px;
  padding: 0 0 48px;
  /* Fix wrong positioning in Firefox 9 & older (bug 450418) */
  text-indent: 120%;
  white-space: nowrap;
  overflow: hidden;
  background: none;
  border: 0;
  border-radius: 24px;
  cursor: pointer;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.2), 0 1px rgba(255, 255, 255, 0.1);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.2), 0 1px rgba(255, 255, 255, 0.1);
  /* Must use another pseudo element for the gradient background because Webkit */
  /* clips the background incorrectly inside elements with a border-radius.     */
}
.login-button:before {
  content: '';
  position: absolute;
  top: 5px;
  bottom: 5px;
  left: 5px;
  right: 5px;
  background: #00a2d3;
  border-radius: 24px;
  background-image: -webkit-linear-gradient(top, #00a2d3, #0d7796);
  background-image: -moz-linear-gradient(top, #00a2d3, #0d7796);
  background-image: -o-linear-gradient(top, #00a2d3, #0d7796);
  background-image: linear-gradient(to bottom, #00a2d3, #0d7796);
  -webkit-box-shadow: inset 0 0 0 1px #00a2d3, 0 0 0 5px rgba(0, 0, 0, 0.16);
  box-shadow: inset 0 0 0 1px #00a2d3, 0 0 0 5px rgba(0, 0, 0, 0.16);
}
.login-button:active:before {
  background: #0591ba;
  background-image: -webkit-linear-gradient(top, #0591ba, #00a2d3);
  background-image: -moz-linear-gradient(top, #0591ba, #00a2d3);
  background-image: -o-linear-gradient(top, #0591ba, #00a2d3);
  background-image: linear-gradient(to bottom, #0591ba, #00a2d3);
}
.login-button:after {
  content: '';
  position: absolute;
  top: 15px;
  left: 12px;
  width: 25px;
  height: 19px;
  background: url("../img/arrow.png") 0 0 no-repeat;
}

::-moz-focus-inner {
  border: 0;
  padding: 0;
}

.lt-ie9 input[type=text], .lt-ie9 input[type=password] {
  line-height: 40px;
  background: #282828;
}
.lt-ie9 .login-submit {
  position: absolute;
  top: 12px;
  right: -28px;
  padding: 4px;
}
.lt-ie9 .login-submit:before, .lt-ie9 .login-submit:after {
  display: none;
}
.lt-ie9 .login-button {
  line-height: 48px;
}
.lt-ie9 .about {
  background: #313131;
}

.header h1 {
	margin-top: 20px;
	color: #FFFFFF;
	font-weight: bold;
	text-align: center;
	font-size: 30px;
}



</style>
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>

<body>

<div class="header"><h1>Login to CCA Schedule</h1></div>

<?php
echo '<form method="post" class="login" action="';
echo htmlspecialchars($_SERVER["PHP_SELF"]);
echo '">';
?> 
 <p>
      <label for="login">Username:</label>
      <input type="text" name="user" id="login" value="">
    </p>

    <p>
      <label for="password">Password:</label>
      <input type="password" name="pass" id="password" value="">
    </p>
    
	  <p>
      <label>Year:</label>
      <select name="year" id="year">
       <option value="2016">2016</option>
        <option value="2017">2017</option>
        <option value="ProfDev">ProfDev</option></select>
    </p>

    <p class="login-submit">
      <button type="submit" class="login-button">Login</button>
    </p>

  <p class="forgot-password"><a href="changepw.php">Change your Password</a></p>
  </form>



</body>
</html>


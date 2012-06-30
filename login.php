<?php
session_start();

include ("include/config.php"); 
include ("include/user.class.php");

if(isset($_SESSION['user_id']))
{
	$new_user = new user;
	$new_user->logout();
}
if(isset($_POST['submit']))
{
	$msg = "";
	$new_user = new user;
	$msg = $new_user->login($_POST['username'], $_POST['password']);
	print_r("<br/>".$msg);
	if($msg == "success")
	{
		header("location: index.php");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<link type="text/css" rel="stylesheet" href="include/style.css"/>
</head>

<body style="margin: 0 auto;text-align:center;">
<div class="login">
    <form action="login.php" method="post" >
       Username: <input type="text" name="username" value="test"/> <br/><br/>
       Password: <input type="password" name="password" value="test"/> <br/><br/>
       <input type="submit" name="submit" value="Login" />
    </form>
</div>
</body>
</html>

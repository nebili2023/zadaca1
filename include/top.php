<?php
session_start();
if(!isset($_SESSION['user_id']))
{
	header("location: login.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Products manager</title>
<link type="text/css" rel="stylesheet" href="include/style.css"/>
</head>
<body>
<div class="container">
    <div class="menu">
    <ul style="display: inline;">
        <li><a href="index.php">home</a></li>
        <li><a href="categories.php">categories</a></li>
        <li><a href="products.php">products</a></li>
    </ul>
    <a href="login.php"  style="float:right; margin-right: 50px;" >Logout</a>
    </div>
    <div class="main">

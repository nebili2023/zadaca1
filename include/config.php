<?php

$db_name = "test";
$db_host = "localhost";
$db_user = "root";
$db_pass = "";


$db = @mysql_connect($db_host, $db_user, $db_pass);

if(!$db || !(@mysql_select_db($db_name,$db)))
{
    die('Unable to connect or select database!');
}

function close_db($db)
{
	mysql_close($db);
}

?>
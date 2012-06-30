<?php

class user{

	function login($username, $password){
		
		if( !isset($username) || $username == '' || !isset($password) || $password == '' )
		{
			$msg = "Insert username and/or password";
		}
		else
		{
			$status = $this->check_user($username, $password);
			if( $status == "OK" )
			{
				$msg = "success";
			}
			else
			{
				$msg = "Wrong username and/or password";
			}
		}
		return $msg;
	}
	
	function check_user($username, $password)
	{
		$sql = "SELECT * FROM users WHERE username='".$username."' AND password='".$password."';" ;
		$this_user = mysql_fetch_array(mysql_query($sql));
		
		if(isset($this_user['username']) and isset($this_user['password']))
		{
			$_SESSION['user_id'] = $this_user['id'];
			$status = "OK";
		}
		else
		{
			$status = "error";
		}
		return $status;
	}
	
	function logout(){
		unset($_SESSION['user_id']);
	}

}

?>
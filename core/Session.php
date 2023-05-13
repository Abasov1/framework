<?php

namespace app\core;

class Session{

	public static function set($key,$value,$time = 0){
		session_set_cookie_params($time);
		session_start();
		$_SESSION[$key] = $value;
		session_write_close();
	}	

	public static function get($key){
		session_start();
		if(isset($_SESSION[$key])){
			session_write_close();
			return $_SESSION[$key];
		}else{
			session_write_close();
			return false;
		}
	}

	public static function remove($key){
		session_start();
		unset($_SESSION[$key]);
		session_write_close();
	}

}

?>
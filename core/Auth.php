<?php

namespace app\core;

use app\core\Hash;
use app\core\Application;
class Auth{

	public static function login($credentials){
		$error = [];
		$db = mysqli_connect('localhost',Application::$env['DB_USER'],Application::$env['DB_PASSWORD'],Application::$env['DB_NAME']);
		$query = mysqli_query($db,"SELECT * FROM users WHERE email = '".$credentials['email']."' ");
		if(mysqli_num_rows($query) < 1) {
			$error['email'] = "This email doesn't exists";
 		    self::back(['error'=>$error,'old'=>$credentials]);
		} 
		$user = mysqli_fetch_object($query);
		if(Hash::verify($credentials['password'],$user->password)){
			if(isset($credentials['remember'])){
				Session::set('USER',$user,60*60*24*7);
			}else{
				Session::set('USER',$user,0);
			}
			return true;
		}else{
			$error['password'] = "Password is wrong";
			self::back(['error'=>$error,'old'=>$credentials]);
		}
	}

	public static function logout(){
		Session::remove('USER');
	}

	public static function back($messages = false){
		if($messages){
			Session::set('REDIRECT_MESSAGES',$messages,60);
		}
		$rut = $_SERVER['HTTP_REFERER']; 
		header("Location: $rut");
		die();
	}

}

?>
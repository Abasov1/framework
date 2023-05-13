<?php

namespace app\core;

use app\core\Hash;
use app\core\Application;
class Auth{

	public static function login($email,$password,$remember = false){
		$db = mysqli_connect('localhost',Application::$cfg['user'],Application::$cfg['password'],Application::$cfg['name']);
		$query = mysqli_query($db,"SELECT * FROM users WHERE email = '$email' ");
		$user = mysqli_fetch_object($query);
		if(Hash::verify($password,$user->password)){
			if($remember){
				Session::set('USER',$user,60*60*24*7);
			}else{
				Session::set('USER',$user);
			}
			return true;
		}else{
			return false;
		}
	}

	public static function logout(){
		Session::remove('USER');
	}

}

?>
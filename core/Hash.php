<?php

namespace app\core;

class Hash{

	public static function make($password){
		return password_hash($password, PASSWORD_DEFAULT);
	}

	public static function verify($password,$hash){
		return password_verify($password, $hash);
	}

	public static function find($crs){
		if(isset($crs['password'])){
			$crs['password'] = password_hash($crs['password'], PASSWORD_DEFAULT);
		}
		return $crs;
	}
}


?>
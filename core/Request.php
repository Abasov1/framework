<?php

namespace app\core;

use app\core\Application;
use app\core\Session;
class Request{

	public static $validations = [
		'required',
		'min',
		'max',
		'unique'
	];
	public static $db;

	public static function connect(){
		self::$db = mysqli_connect('localhost',Application::$cfg['user'],Application::$cfg['password'],Application::$cfg['name']);
	}

	public static $error = false;

	public static function validate($credentials,$validations = false,$messages = false){
		if(get_called_class() != self::class){
			$called = get_called_class();
			$instance = new $called();
			$validations = $instance->rules;
			$messages = $instance->messages;
		}
		if(!$validations){
			echo 'Validation rules are required';
			die();
		}
		self::connect();
		self::valcheck($validations);
		$vay = false;
		foreach($validations as $key => $value){
			foreach($value as $val){
				$yuh = explode(":", $val);
				if($val === 'required'){
					if(empty($credentials[$key]) || strlen($credentials[$key]) === 0){
						if(isset($messages[$key.'.'.$val])){
							self::$error[$key] = $messages[$key.'.'.$val]; 
						}else{
							self::$error[$key] = $key. ' is required'; 
						}
						$credentials[$key] = null;
						$vay = true;
					}
				}elseif($yuh[0] === 'min' && !$vay){
					if(strlen($credentials[$key]) < $yuh[1]){
						if(isset($messages[$key.'.'.$yuh[0]])){
							self::$error[$key] = $messages[$key.'.'.$yuh[0]];
						}else{
							self::$error[$key] = $key. ' should be at least '.$yuh[1]. ' characters';
						}
						$vay = true;
					}
				}elseif($yuh[0] === 'max' && !$vay){
					if(strlen($credentials[$key]) > $yuh[1]){
						if(isset($messages[$key.'.'.$yuh[0]])){
							self::$error[$key] = $messages[$key.'.'.$yuh[0]];
						}else{
							self::$error[$key] = $key. ' should be max '.$yuh[1]. ' characters';
						}
						$vay = true;
					}
				}elseif($yuh[0] === 'unique' && !$vay){
					$what = explode(",",$yuh[1]);
					$find = mysqli_query(self::$db,"SELECT * FROM $what[0] WHERE $key = '$credentials[$key]' ");
					$result = mysqli_fetch_array($find);
					if($result){
					if(count($what) === 2){
						if($what[1] !== $result['id']){
							if(isset($messages[$key.'.'.$yuh[0]])){
								self::$error[$key] = $messages[$key.'.'.$yuh[0]]; 
							}else{
								self::$error[$key] = "This ".$key." is already exists";
							}
					    	$vay = true;
						}
					}else{
						if(isset($messages[$key.'.'.$yuh[0]])){
							self::$error[$key] = $messages[$key.'.'.$yuh[0]]; 
						}else{
							self::$error[$key] = "This ".$key." is already exists";
						}
					    $vay = true;
						}
					}	
				}
			}
		}
		$die = false;
		$remember = false;
		$return = false;
		if($vay){
			self::back(['error'=>self::$error,'old'=>$credentials]);
			die();
		}else{
			return true;
		}
	}

	public static function valcheck($vals){
		foreach($vals as $key=>$value){
			foreach ($value as $val) {
				if(!in_array($val,self::$validations)){
					$yuh = explode(":",$val);
					if(!in_array($yuh[0], self::$validations)){
						echo 'couldnt find '.$yuh[0].' in validation list';
						die();
					}
				}
			}
		}
	}

	//Getting the url
	public function getPath(){
		$path = $_SERVER['REQUEST_URI'] ?? '/';
		$meth = explode('?', $path);
		return $meth[0];
	}

	//Checking the request method (get,post,put)
	public function getMethod(){
		return strtolower($_SERVER['REQUEST_METHOD']);
	}

	//Giving $request
	public function get(){
		$body = [];

		if($this->getMethod() === 'get'){
			foreach($_GET as $key => $value){
				$body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}

		if($this->getMethod() === 'post'){
			foreach($_POST as $key => $value){
				$body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		}

		return $body;
	}
	public static function back($messages = false){
		if($messages){
			Session::set('REDIRECT_MESSAGES',$messages,60);
		}
		$rut = $_SERVER['HTTP_REFERER']; 
		header("Location: $rut");
	}
}

?>
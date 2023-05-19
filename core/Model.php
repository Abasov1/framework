<?php

namespace app\core;	

use app\core\Application;

class Model{
	public static $db;

	protected static $table;
	protected static $fillable;

	public static function connect(){
		self::$db = mysqli_connect('localhost',Application::$cfg['user'],Application::$cfg['password'],Application::$cfg['name']);
	}

	public static function getTablee(){
		self::$table = call_user_func_array([get_called_class(),'getTable'],['aq']);
		return self::$table;
	}

	public static function getFillablee(){
		self::$fillable = call_user_func_array([get_called_class(),'getFillable'],['aq']);
		return self::$fillable;
	}
	
	public static function where($column,$value){
		self::connect();
		$table = self::getTablee();
		$query = mysqli_query(self::$db,"SELECT * FROM $table WHERE $column = '$value'");
		$oyy = mysqli_fetch_object($query);
		$instance = new self();
	    $instance->instance = $oyy;
	    $instance->teble = $table;
	    return $instance->instance;
	}

	public static function all(){
		self::connect();
		$table = self::getTablee();
		$query = mysqli_query(self::$db,"SELECT * FROM $table");
		$result = mysqli_fetch_all($query, MYSQLI_ASSOC);

		foreach ($result as &$row) {
		    $row = (object) $row; // convert each row to an object
		}

		return $result;
	}
	
	public function delete(){
		$id = $this->instance->id;
		$table = $this->teble;
		$delete = mysqli_query(self::$db,"DELETE FROM $table WHERE id = $id ");
	}
	
	public static function check($credentials){
		self::getFillablee();
		if(in_array('id',array_keys($credentials))){
			self::$fillable[] = 'id';
		}
		foreach ($credentials as $cr => $br) {
			if(!in_array($cr, self::$fillable)){
				echo 'couldnt find '.$cr.' in fillable list';
				die();
			}
		}
		if(in_array('id',array_keys(self::$fillable))){
			unset(self::$fillable['id']);
		}
	}

	public static function create($credentials){
		self::check($credentials);
		self::connect();
		$table = self::getTablee();
		foreach($credentials as $key => $value){
			if(empty($value)){
				echo $key . ' is empty';
				die();
			}
		}
		$kuyis = array_keys($credentials);
		$lastKey = end($kuyis);
		$kuri = "INSERT INTO $table SET ";
		foreach($credentials as $key => $value){
			if($key !== 'id' && $key !== $lastKey){
				$kuri .= $key. " = '" .$value."',";
			}elseif($key === $lastKey){
				$kuri .= $key. " = '" .$value."'";
			}
		}
		$query = mysqli_query(self::$db,$kuri);
		if($query){
			$id = mysqli_insert_id(self::$db);	
			$q_user = mysqli_query(self::$db,"SELECT * FROM users WHERE id = $id ");
			return $user = mysqli_fetch_object($q_user);
		}else{
			echo "Query failed: " . mysqli_error(self::$db);
			die();
		}
	}

	public static function update($credentials){
		$id = $credentials['id'];
		self::check($credentials);
		self::connect();
		$table = self::getTablee();
		foreach($credentials as $key => $value){
			if(empty($value)){
				echo $key . ' is empty';
				die();
			}
		}
		$kuyis = array_keys($credentials);
		$lastKey = end($kuyis);
		$kuri = "UPDATE $table SET ";
		foreach($credentials as $key => $value){
			if($key !== 'id' && $key !== $lastKey){
				$kuri .= $key. " = '" .$value."',";
			}elseif($key === $lastKey){
				$kuri .= $key. " = '" .$value."'";
			}
		}
		$kuri .= " WHERE id = '".$id."'";
		$query = mysqli_query(self::$db,$kuri);
		if($query){
			$id = mysqli_insert_id(self::$db);	
			$q_user = mysqli_query(self::$db,"SELECT * FROM users WHERE id = $id ");
			return $user = mysqli_fetch_object($q_user);
		}else{
			return false;
		}
	}
}

?>
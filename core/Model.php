<?php

namespace app\core;	

use app\core\Application;

class Model{
	public static $db;

	public static function connect(){
		self::$db = mysqli_connect('localhost',Application::$env['DB_USER'],Application::$env['DB_PASSWORD'],Application::$env['DB_NAME']);
	}

	public static function getTablee(){
		if(get_called_class() != self::class){
			$called = get_called_class();
			$instance = new $called();
			return $instance->table;
		}
	}

	public static function getFillablee(){
		if(get_called_class() != self::class){
			$called = get_called_class();
			$instance = new $called();
			return $instance->fillable;
		}
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
		$fillable =  self::getFillablee();
		if(in_array('id',array_keys($credentials))){
			$fillable[] = 'id';
		}
		foreach ($credentials as $cr => $br) {
			if(!in_array($cr, $fillable)){
				echo 'couldnt find '.$cr.' in fillable list';
				die();
			}
		}
		if(in_array('id',array_keys($fillable))){
			unset($fillable['id']);
		}
	}

	public static function create($credentials){
		self::check($credentials);
		self::connect();
		$table = self::getTablee();
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
			$q_user = mysqli_query(self::$db,"SELECT * FROM $table WHERE id = $id ");
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
			$q_user = mysqli_query(self::$db,"SELECT * FROM $table WHERE id = $id ");
			return $user = mysqli_fetch_object($q_user);
		}else{
			return false;
		}
	}
}

?>
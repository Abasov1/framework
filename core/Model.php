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
	
	public static function where($column,$value = false){

		self::connect();
		$table = self::getTablee();
		$sql = "SELECT * FROM $table WHERE ";
		if(is_array($column)){
			$akiy = array_keys($column);
			$end = end($akiy);
			foreach ($column as $acar => $deyer) {
				if(is_string($deyer)){
					if($acar === $end){
						$sql .= "$acar = '$deyer' ";
					}else{
						$sql .= "$acar = '$deyer' AND ";
					}
				}elseif($deyer === null){
					if($acar === $end){
						$sql .= "$acar IS NULL ";
					}else{
						$sql .= "$acar IS NULL AND ";
					}
				}else{
					if($acar === $end){
						$sql .= "$acar = $deyer ";
					}else{
						$sql .= "$acar = $deyer AND ";
					}
				}
			}
		}else{
			if(is_string($value)){
				$sql .= "$column = '$value' ";
			}elseif($value === null){
				$sql .= "$column IS NULL ";
			}else{
				$sql .= "$column = $value ";
			}
		}
		$called = get_called_class();
		$instance = new $called();
		$instance->teble = $table;
		$instance->sql = $sql;
	    return $instance;
	}

	public static function whereIn($gerray, $qarray = false){
		self::connect();
		$table = self::getTablee();
		$sql = "SELECT * FROM $table WHERE ";
		if(is_array($gerray)){
			$gend = array_keys($gerray);
			$end = end($gend);
			foreach($gerray as $key => $value){
				if(!is_array($value)){
					die("Wrong whereIn usage".$key);
				}
				if($key === $end){
					$sql .= "$key IN(";
					$tend = end($value);
					foreach ($value as $val) {
						if(is_string($val)){
							if($tend === $val){
							    $sql .= "'$val'";
							}else{
							    $sql .= "'$val'" . ",";
							}
						}else{
							if($tend === $val){
							    $sql .= $val;
							}else{
							    $sql .= $val . ",";
							}
						}
					}
					$sql .= ") ";
				}else{
					$sql .= "$key IN(";
					$tend = end($value);
					foreach ($value as $val) {
						if(is_string($val)){
							if($tend === $val){
							    $sql .= "'$val'";
							}else{
							    $sql .= "'$val'" . ",";
							}
						}else{
							if($tend === $val){
							    $sql .= $val;
							}else{
							    $sql .= $val . ",";
							}	
						}
					}
					$sql .= ") AND ";
				}
			}
		}elseif(is_array($qarray)){
			$gend = array_keys($qarray);
			$end = end($gend);
			$sql .= "$gerray IN(";
			$tend = end($qarray);
			foreach ($qarray as $val) {
				if(is_string($val)){
					if($tend === $val){
					    $sql .= "'$val'";
					}else{
					    $sql .= "'$val'" . ",";
					}
				}else{
					if($tend === $val){
					    $sql .= $val;
					}else{
					    $sql .= $val . ",";
					}
				}
			}
			$sql .= ") ";
		}else{
			die("Wrong whereIn usage");
		}
		$called = get_called_class();
		$instance = new $called();
		$instance->teble = $table;
		$instance->sql = $sql;
		return $instance;
	}

	public static function whereLike($column,$value = false){
		self::connect();
		$table = self::getTablee();
		$sql = "SELECT * FROM $table WHERE ";
		if(is_array($column)){
			$akiy = array_keys($column);
			$end = end($akiy);
			foreach ($column as $acar => $deyer) {
				if($acar === $end){
					$sql .= "$acar LIKE '%$deyer%' ";
				}else{
					$sql .= "$acar LIKE '%$deyer%' OR ";
				}
			}
		}else{
			$sql .= "$column LIKE '%$value%' ";
		}
		$called = get_called_class();
		$instance = new $called();
		$instance->teble = $table;
		$instance->sql = $sql;
	    return $instance;
	}
	
	public function orWhere($column,$value = false){
		$sql = $this->sql;
		$table = $this->teble;
		$sql .= "OR ";
		if(is_array($column)){
			$akiy = array_keys($column);
			$end = end($akiy);
			foreach ($column as $acar => $deyer) {
				if(is_string($deyer)){
					if($acar === $end){
						$sql .= "$acar = '$deyer' ";
					}else{
						$sql .= "$acar = '$deyer' AND ";
					}
				}elseif($deyer === null){
					if($acar === $end){
						$sql .= "$acar IS NULL ";
					}else{
						$sql .= "$acar IS NULL AND ";
					}
				}else{
					if($acar === $end){
						$sql .= "$acar = $deyer ";
					}else{
						$sql .= "$acar = $deyer AND ";
					}
				}
			}
		}else{
			if(is_string($value)){
				$sql .= "$column = '$value' ";
			}elseif($value === null){
				$sql .= "$column IS NULL ";
			}else{
				$sql .= "$column = $value ";
			}
		}
		$this->sql = $sql;
	    return $this;
	}

	public function orWhereIn($gerray, $qarray = false){
		$sql = $this->sql;
		$table = $this->teble;
		$sql .= "OR ";
		if(is_array($gerray)){
			$gend = array_keys($gerray);
			$end = end($gend);
			foreach($gerray as $key => $value){
				if(!is_array($value)){
					die("Wrong whereIn usage".$key);
				}
				if($key === $end){
					$sql .= "$key IN(";
					$tend = end($value);
					foreach ($value as $val) {
						if(is_string($val)){
							if($tend === $val){
							    $sql .= "'$val'";
							}else{
							    $sql .= "'$val'" . ",";
							}
						}else{
							if($tend === $val){
							    $sql .= $val;
							}else{
							    $sql .= $val . ",";
							}
						}
					}
					$sql .= ") ";
				}else{
					$sql .= "$key IN(";
					$tend = end($value);
					foreach ($value as $val) {
						if(is_string($val)){
							if($tend === $val){
							    $sql .= "'$val'";
							}else{
							    $sql .= "'$val'" . ",";
							}
						}else{
							if($tend === $val){
							    $sql .= $val;
							}else{
							    $sql .= $val . ",";
							}	
						}
					}
					$sql .= ") AND ";
				}
			}
		}elseif(is_array($qarray)){
			$gend = array_keys($qarray);
			$end = end($gend);
			$sql .= "$gerray IN(";
			$tend = end($qarray);
			foreach ($qarray as $val) {
				if(is_string($val)){
					if($tend === $val){
					    $sql .= "'$val'";
					}else{
					    $sql .= "'$val'" . ",";
					}
				}else{
					if($tend === $val){
					    $sql .= $val;
					}else{
					    $sql .= $val . ",";
					}
				}
			}
			$sql .= ") ";
		}else{
			die("Wrong whereIn usage");
		}
		$this->sql = $sql;
	    return $this;
	}

	public function orWhereLike($column,$value = false){
		$sql = $this->sql;
		$table = $this->teble;
		$sql .= "OR ";
		if(is_array($column)){
			$akiy = array_keys($column);
			$end = end($akiy);
			foreach ($column as $acar => $deyer) {
				if($acar === $end){
					$sql .= "$acar LIKE '%$deyer%' ";
				}else{
					$sql .= "$acar LIKE '%$deyer%' OR ";
				}
			}
		}else{
			$sql .= "$column LIKE '%$value%' ";
		}
		$this->sql = $sql;
	    return $this;
	}

	public function andWhere($column,$value = false){
		$sql = $this->sql;
		$table = $this->teble;
		$sql .= "AND ";
		if(is_array($column)){
			$akiy = array_keys($column);
			$end = end($akiy);
			foreach ($column as $acar => $deyer) {
				if(is_string($deyer)){
					if($acar === $end){
						$sql .= "$acar = '$deyer' ";
					}else{
						$sql .= "$acar = '$deyer' AND ";
					}
				}elseif($deyer === null){
					if($acar === $end){
						$sql .= "$acar IS NULL ";
					}else{
						$sql .= "$acar IS NULL AND ";
					}
				}else{
					if($acar === $end){
						$sql .= "$acar = $deyer ";
					}else{
						$sql .= "$acar = $deyer AND ";
					}
				}
			}
		}else{
			if(is_string($value)){
				$sql .= "$column = '$value' ";
			}elseif($value === null){
				$sql .= "$column IS NULL ";
			}else{
				$sql .= "$column = $value ";
			}
		}
		$this->sql = $sql;
	    return $this;
	}

	public function andWhereIn($gerray, $qarray = false){
		$sql = $this->sql;
		$table = $this->teble;
		$sql .= "AND ";
		if(is_array($gerray)){
			$gend = array_keys($gerray);
			$end = end($gend);
			foreach($gerray as $key => $value){
				if(!is_array($value)){
					die("Wrong whereIn usage".$key);
				}
				if($key === $end){
					$sql .= "$key IN(";
					$tend = end($value);
					foreach ($value as $val) {
						if(is_string($val)){
							if($tend === $val){
							    $sql .= "'$val'";
							}else{
							    $sql .= "'$val'" . ",";
							}
						}else{
							if($tend === $val){
							    $sql .= $val;
							}else{
							    $sql .= $val . ",";
							}
						}
					}
					$sql .= ") ";
				}else{
					$sql .= "$key IN(";
					$tend = end($value);
					foreach ($value as $val) {
						if(is_string($val)){
							if($tend === $val){
							    $sql .= "'$val'";
							}else{
							    $sql .= "'$val'" . ",";
							}
						}else{
							if($tend === $val){
							    $sql .= $val;
							}else{
							    $sql .= $val . ",";
							}	
						}
					}
					$sql .= ") AND ";
				}
			}
		}elseif(is_array($qarray)){
			$gend = array_keys($qarray);
			$end = end($gend);
			$sql .= "$gerray IN(";
			$tend = end($qarray);
			foreach ($qarray as $val) {
				if(is_string($val)){
					if($tend === $val){
					    $sql .= "'$val'";
					}else{
					    $sql .= "'$val'" . ",";
					}
				}else{
					if($tend === $val){
					    $sql .= $val;
					}else{
					    $sql .= $val . ",";
					}
				}
			}
			$sql .= ") ";
		}else{
			die("Wrong whereIn usage");
		}
		$this->sql = $sql;
	    return $this;
	}

	public function andWhereLike($column,$value = false){
		$sql = $this->sql;
		$table = $this->teble;
		$sql .= "AND ";
		if(is_array($column)){
			$akiy = array_keys($column);
			$end = end($akiy);
			foreach ($column as $acar => $deyer) {
				if($acar === $end){
					$sql .= "$acar LIKE '%$deyer%' ";
				}else{
					$sql .= "$acar LIKE '%$deyer%' OR ";
				}
			}
		}else{
			$sql .= "$column LIKE '%$value%' ";
		}
		$this->sql = $sql;
	    return $this;
	}

	public static function prepare(){
		self::connect();
		$table = self::getTablee();
		$sql = "SELECT * FROM $table ";
		$called = get_called_class();
		$instance = new $called();
		$instance->teble = $table;
		$instance->sql = $sql;
	    return $instance;
	}

	public function paginate($page,$perPage = 10){
		$sql = $this->sql;
		$offset = ($page - 1) * $perPage;
		$sql .= "LIMIT $offset, $perPage ";
		die($sql);
		$query = mysqli_query(self::$db,$sql);
		$oyy = [];
		while($row = mysqli_fetch_object($query)){
			$oyy[] = $row;
		}

	}
	public function get(){
		$sql = $this->sql;
		$query = mysqli_query(self::$db,$sql);
		$oyy = [];
		while($row = mysqli_fetch_object($query)){
			$oyy[] = $row;
		}
		return $oyy;
	}

	public function pluck($column){
		$sql = $this->sql;
		$sql = str_replace("*",$column,$sql);
		$query = mysqli_query(self::$db,$sql);
		$array = [];
		while ($row = mysqli_fetch_assoc($query)) {
		    $array[] = $row[$column];
		}
		return $array;
	}

	public function orderBy($column,$method){
		$sql = $this->sql;
		if($method === "ASC" || $method === "asc"){
			$sql .= "ORDER BY $column ASC ";
		}elseif($method === "DESC" || $method === "desc"){
			$sql .= "ORDER BY $column DESC ";
		}else{
			die("wrong orderBy usage");
		}
		$this->sql = $sql;
		return $this;
	}

	public function first(){
		$sql = $this->sql;
		$query = mysqli_query(self::$db,$sql);
		$oyy = mysqli_fetch_object($query);
		return $oyy;
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
		$sql = $this->sql;
		$table = $this->teble;
		$sql = str_replace("SELECT *","DELETE",$sql);
		$delete = mysqli_query(self::$db,$sql);
	}
	
	public function exists(){
		$sql = $this->sql;
		$table = $this->teble;
		$sql = "SELECT EXISTS($sql) AS result";
		$query = mysqli_query(self::$db,$sql);
		$fetch = mysqli_fetch_row($query);
		return $fetch[0];
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
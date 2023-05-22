<?php

namespace app\core;

use app\core\Application;
class Table{

	private $value;

	public function tableName($name){
		$this->table = $name;
	}

	public function id(){
		if(isset($this->credentials)){
			$this->credentials['id'] = ",id INT(11) AUTO_INCREMENT PRIMARY KEY";
		}else{
			$this->credentials = [];
			$this->credentials['id'] = "id INT(11) AUTO_INCREMENT PRIMARY KEY";
		}
	}

	public function string($str,$num = 255){
		if(isset($this->credentials)){
			$this->value = ",$str";
			$this->credentials[",$str"] = ",$str VARCHAR($num) NOT NULL";
		}else{
			$this->value = $str;
			$this->credentials = [];
			$this->credentials[$str] = "$str VARCHAR($num) NOT NULL";
		}
		return $this;
	}
	public function integer($int,$num = 11){
		if(isset($this->credentials)){
			$this->value = ",$int";
			$this->credentials[",$int"] = ",$int INT($num) NOT NULL";
		}else{
			$this->value = $int;
			$this->credentials = [];
			$this->credentials[$int] = "$int INT($num) NOT NULL";
		}
		return $this;
	}
	public function default($value){
		if(in_array($this->value,array_keys($this->credentials))){
			if(strpos($this->credentials[$this->value], "NOT NULL")){
				$this->credentials[$this->value] = str_replace("NOT NULL", "DEFAULT '$value' NOT NULL", $this->credentials[$this->value]);
			}elseif(strpos($this->credentials[$this->value], "NULL")){
				$this->credentials[$this->value] = str_replace("NULL", "DEFAULT '$value' NOT NULL", $this->credentials[$this->value]);
			}else{
				$this->credentials[$this->value] = $this->credentials[$this->value]. " DEFAULT '$value' NOT NULL";
			}
		}else{
			echo 'There is a mistake with position of default method';
			exit;
		}
		return $this;
	}
	public function nullable(){
		if(in_array($this->value,array_keys($this->credentials))){
			$this->credentials[$this->value] = str_replace("NOT NULL", "NULL", $this->credentials[$this->value]);
		}else{
			echo 'There is a mistake with position of nullable method';
			exit;
		}
		return $this;
	}
	public function timestamp($name){
		if(isset($this->credentials)){
			$this->value = ",$name";
			$this->credentials[",$name"] = ",$name TIMESTAMP NOT NULL";
		}else{
			$this->value = $name;
			$this->credentials = [];
			$this->credentials[$name] = "$name TIMESTAMP NOT NULL";
		}
		return $this;
	}
	public function timestamps(){
		if(isset($this->credentials)){
			$this->value = 'time';
			$this->credentials['time'] = ",created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
		}else{
			$this->value = 'time';
			$this->credentials = [];
			$this->credentials['time'] = "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
		}
	}
	public function create(){
		if(isset($this->table) && isset($this->credentials)){
			$kuri = "";
			foreach ($this->credentials as $value) {
				$kuri .= $value;
			}
			$sql = "CREATE TABLE ".$this->table." (".$kuri.") ENGINE=InnoDB;";
			$db = Application::$app->db;
			$db->pdo->exec($sql);
			echo 'Created table: '.$this->table.PHP_EOL;
		}else{
			echo 'Make sure you defined some credentials and tablename with $this->tableName';
		}
	}

	public function drop($table){
		$db = Application::$app->db;
		$db->pdo->exec("DROP TABLE $table;");
	}

}


?>
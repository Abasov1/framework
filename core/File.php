<?php

namespace app\core;

class File{

	public $name;

	public function __construct($name){
		$this->name = $name;
	}

	public function save($name){
		move_uploaded_file($this->temp(), __DIR__."/../public/storage/$name");
	}

	public function name(){
		return $_FILES[$this->name]['name'];
	}

	public function extension(){
		return pathinfo($_FILES[$this->name]['name'],PATHINFO_EXTENSION);
	}

	public function temp(){
		return $_FILES[$this->name]['tmp_name'];
	}

	public function size(){
		return $_FILES[$this->name]['size'];
	}

	public function isFile(){
		return isset($_FILES[$this->name]) && is_uploaded_file($_FILES[$this->name]['tmp_name']) && $_FILES[$this->name]['size'] > 0;
	}

	public static function delete($path){
		unlink(__DIR__."/../public/storage/$path");
	}
}	


?>
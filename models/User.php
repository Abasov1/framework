<?php

namespace app\models;

use app\core\Model;

class User extends Model{
	public static $fillable = [
		'name',
		'email',
		'password',
	];
	
	public static $table = 'users';

	public static function getTable($par){
		return self::$table;
	}
	
	public static function getFillable($par){
		return self::$fillable;
	}
}

?>
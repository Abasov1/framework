<?php

namespace app\models;

use app\core\Model;

class User extends Model{
	public $fillable = [
		'name',
		'email',
		'password',
	];
	
	public $table = 'users'; 
}

?>
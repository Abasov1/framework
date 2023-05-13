<?php

namespace app\requests;

use app\core\Request;

class UserRequest extends Request{

	public $rules = [
		'name' => ['required','min:6','max:10'],
		'email' => ['required'],
		'password' => ['required','min:6']
	];
	
	public $messages = [
		'name.required' => 'lazimdi qaqa'		
	];
	
}

?>
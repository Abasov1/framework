<?php

namespace app\core;

class Str{

	public static function slug($string){
	    $string = preg_replace('/[^A-Za-z0-9\-]/', ' ', $string);

	    $string = strtolower($string);

	    $string = preg_replace('/\s+/', '-', $string);

	    $string = preg_replace('/-+/', '-', $string);

	    $string = trim($string, '-');

	    return $string;
	}

	public static function random($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';

	    $max = strlen($characters) - 1;
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[random_int(0, $max)];
	    }

	    return $randomString;
	}
}


?>
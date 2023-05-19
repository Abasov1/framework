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

}


?>
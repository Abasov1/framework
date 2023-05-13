<?php

namespace app\core;

use app\core\Application;
use app\core\Session;


class Controller{

	public function go($view,$params = []){
		return Application::$app->router->renderView($view,$params);
	}

	public function redirect($route,$messages = false){
		if($messages){
			Session::set('REDIRECT_MESSAGES',$messages,60);
		}
		$rut = Application::$env['APPLICATION_URL'] . $route; 
		header("Location: $rut");
	}

	public function back($messages = false){
		if($messages){
			Session::set('REDIRECT_MESSAGES',$messages,60);
		}
		$rut = $_SERVER['HTTP_REFERER']; 
		header("Location: $rut");
	}
}

?>
<?php

namespace app\routes;

use app\core\Application;
use app\controllers\UserController;

class Route{

	public function __construct(Application $app){
		$app->router->get('/','home');
	}

}


?>
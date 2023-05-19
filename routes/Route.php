<?php

namespace app\routes;

use app\core\Application;

class Route{

	public function __construct(Application $app){
		$app->router->get('/','home');
	}

}


?>
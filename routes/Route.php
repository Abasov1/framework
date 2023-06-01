<?php

namespace app\routes;

use app\core\Application;
use app\controllers\Base;

class Route{

	public function __construct(Application $app){
		$app->router->get('/','home');
	}

}


?>
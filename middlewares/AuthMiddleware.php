<?php

namespace app\middlewares;

use app\core\Middleware;

class AuthMiddleware extends Middleware{

	public function allow(){
		if(auth()){	
			return true;
		}else{
			return false;
		}
	}

}

?>
<?php

namespace app\middlewares;

class AuthMiddleware{

	public function allow(){
		if(auth()){
			return true;
		}else{
			return false;
		}
	}

}

?>
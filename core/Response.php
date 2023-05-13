<?php

namespace app\core;

class Response{

	//Setting status code
	public function setStatusCode(int $code){
		http_response_code($code);
	}

}

?>
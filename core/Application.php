<?php 
	
namespace app\core;

class Application{

	public Request $request;
	public Router $router;
	public Response $response;
	public Database $db;

	public static Application $app;
	public static $cfg;
	public static $env;
	
	public function __construct($cfg,$env){
		self::$app = $this;
		self::$cfg = $cfg;
		self::$env = $env;
		
		$this->request = new Request();
		$this->response = new Response();
		$this->router = new Router($this->request,$this->response);
		$this->db = new Database($cfg);
	}

	public function run(){
		$this->router->resolve();
	}
} 

?>
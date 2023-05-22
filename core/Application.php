<?php 
	
namespace app\core;

class Application{

	public Request $request;
	public Router $router;
	public Response $response;
	public Database $db;

	public static Application $app;
	public static $env;
	
	public function __construct($env){
		self::$app = $this;
		self::$env = $env;
		
		$this->request = new Request();
		$this->response = new Response();
		$this->router = new Router($this->request,$this->response);
		$this->db = new Database($env);
	}

	public function run(){
		$this->router->resolve();
	}
} 

?>
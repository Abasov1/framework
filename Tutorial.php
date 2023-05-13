<?php

//To start the server go to the public file and use php -S localhost:8000

//Routes: 

	//routes/Route.php
		
		//Return view directly - second parameter

		$app->router->get('/home','home');

		//Redirect to the controller - second parameter
		$app->router->get('/home',[UserController::class,'index']);

		//Use middlewares - third parameter
		$app->router->get('/home',[UserController::class,'index'],['auth','model']);
//Views

	//views/

		/*To define layout go into a view and use {{layout-'the directory of layout'}} and then
		u can use that view with defining it's position in the layout using the {{content-'the directory of your view'}} */

//Models

	//To define a model:

		use app\core\Model;

		class Something extends Model{
			public static $fillable = [
				'colum1',
				'colum2',
				'colum3',
			];
			
			public static $table = 'something';

			public static function getTable($par){
				return self::$table;
			}
			
			public static function getFillable($par){
				return self::$fillable;
			}


		}

	//And then you can use this methods

		Something::create([
			'column1' => 'value1',
			'column2' => 'value2',
			'column3' => 'value3'
		]); //Credentials must an array and 

		Something::update([
			'id' => 1
			'column1' => 'value1',
			'column2' => 'value2',
			'column3' => 'value3'
		]); //To update a model you gotta give its id

		Something::where('column','value'); //Returns that column

		Something::where('column','value')->delete(); //To delete that column

//Validation
	// There are only 4 validation rules: required,min,max and unique
	
	// app\core\Request

		//First parameter - Credentials 
		//Second parameter - Validation rules
		//Third parameter - Messages (optional)

		Request::validate($credentials,[
			'name' => ['required','min:6','max:10'],
			'email' => ['required','unique:users'],
			'password' => ['required','min:6']
		],[
			'name.required' => 'Name is required'			
		]); 



	//If you want to ignore that column when using unique you gotta give its id
		'email' => ['unique:users,'.$id]

	//And if you want to create your own validation rule this is the example for it:
		namespace app\requests;

		use app\core\Request;

		class UserRequest extends Request{

			public $rules = [
				'name' => ['required','min:6','max:10'],
				'email' => ['required'],
				'password' => ['required','min:6']
			];
			
			public $messages = [
				'name.required' => 'Name is required'		
			];
			
		}

		//And then you can use it just by passing the credentials inside of it like this:
			UserRequest::validate($body);

//Controllers

	//Default controller:
				namespace app\controllers;

				use app\core\Controller;

				class UserController extends Controller{

					public function index(){
						return $this->go('home');	
					}

					public function register(Request $request){
						$credentials = $request->get(); //To get the components of the request you gotta use the get() method
					}

//Redirections

	//There are only 3 type of redirection in controller / middleware
		$this->go('somewhere',$params); //Go to a view
		
		$this->redirect('/somewhere',$params); // Go to a route?

		$this->back($params); // Go back

//Middlewares 

	//This is a middleware example:
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

	//To use a middleware on routes you gotta first define it in the migrations/Kernel.php

		use app\middlewares\AuthMiddleware;

		return [
			'auth' => [AuthMiddleware::class,'AuthMiddleware.php'] //The second parametr is used to define the route
		];

//Authentication

	// Use auth() to get the authenticated user in middleware or in the view files

//Migrations 

	/*use php create_migration.php to create migration, use php dropall.php to drop all the tables, use php migrate.php to migrate all the migrations
	and use php migrate_refresh.php to refresh all the migrations */

	//Creating tables

		//This is an example of migration: 
			use app\core\Application;
			use app\core\Table;

			return new class extends Table{

				public function up(){
					$this->tableName('users'); // To set the name of the table
					$this->id(); 
					$this->string('name')->default('something');
					$this->string('email');
					$this->string('password');
					$this->integer('age',3)->default(18)->nullable();
					$this->timestamp();
					$this->create(); //To create the table
				}

				public function down(){
					$this->drop('users');
				}

			}



?>
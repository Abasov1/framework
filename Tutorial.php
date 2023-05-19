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

	//You can also define a parameters with {} for example:

		$app->router->get('/home/{id}','home');

		//If you pass that id to the url you can use it on your view like this:

			@if(isset($id))
				{{{$id}}}
			@endif

		//You can also get parameters from the controllers for example:

		$app->router->get('/home/{id}/{name}',[UserController::class,'index']); // Let's say our url is localhost:8000/home/6/maqa
		
		class SomeController extends Controller{

			public function index($params){
				$id = $params['id'];
				$name = $params['name'];

				echo $id; //returns 6
				echo $name; //returns maqa
			}

		}	
//Views

	//views/

		/*To define layout go into a view and use {{layout-'the directory of layout'}} and then
		u can use that view with defining it's position in the layout using the {{content-'the directory of your view'}} */

	//Shortcuts

		// We have @if @else @endif , @foreach @endforeach, @auth @endauth, auth() and {{{ }}} - (shortcut for php echo) shortcuts

		// auth() returns the informations of authenticated user

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
		
		Something::all(); //Returns everything

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

		//All parameters are optional

//Middlewares 

	//This is a middleware example:
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

	//To use a middleware on routes you gotta first define it in the migrations/Kernel.php

		use app\middlewares\AuthMiddleware;

		return [
			'auth' => [AuthMiddleware::class,'AuthMiddleware.php'] //The second parametr is used to define the route
		];

//Authentication

	// Use auth() to get the authenticated user in middleware or in the view files

	// To authenticate a user 

		use app\core\Auth;

		// Use Auth::login to authenticate

		$credentials = [
			'email' => 'admin@gmail.com',
			'password' => 'admin123',
			'remember' => false
		];	

		Auth::login($credentials); // You can only give email and password and it only works on users table

		Auth::logout(); // Use this to logout from authenticated user

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

//File

	use app\core\File;

	$file = new File('filename'); //do define new file class you can use this and the filename should be the name of the input file

	$file->name(); //Returns the original name of the file

	$file->temp(); //Returns the temporary name of the file

	$file->extension(); //Returns the extension of the file

	$file->size(); //Returns the size of the file

	$file->isFile(); //Check if the file exists or not

	$file->save('img/something.png'); //To save selected file (Make sure that the directory is exists)

	File::delete('img/something.png'); //To delete a file

	//These files are storing inside of public/storage folder

//Asset usage

	/* If you want to use some css or js files you can upload those files inside of public folder and then use it from there. For example
	 if you have js/index.js inside of pulbic folder you can say */
  	
  	<script src="js/index.js">

  	//Make sure in the top of the <head> tag in your base html file you are using 

  	<base href="/">


//Support

  	// Session

	  	use app\core\Session;

	  	Session::set($key,$value,$time); // To set a session 

	  	Session::get($key) // To get a value

	  	Session::remove($key) // To end the session

	//Hash 

	  	use app\core\Hash;

	  	$hash = Hash::make('password123'); // To encrypt the value

	  	Hash::verify('password123',$hash); // To check if the ecrypted value is correct

	//Str 

	  	use app\core\Str;

	  	Str::slug('Some stRing'); //Returns some-string

?>
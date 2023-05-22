<?php  

namespace app\core;
use app\core\Request;
use app\core\Response;
use app\core\Application;
use app\core\Session;
use app\core\Middleware;
class Router{

	protected $routes = [];
	protected $middlewares = [];

	public function __construct(Request $request,Response $response){
		$this->request = $request; 
		$this->response = $response; 
	}

	public function get($path,$callback,$middleware = false){
		$kernel = include __DIR__."/../middlewares/Kernel.php";
		if($middleware){
			if(is_array($middleware)){
				foreach($middleware as $mid){
					if(!in_array($mid,array_keys($kernel))){
						echo 'No such middleware '.$mid;
						die();
					}
				}
				$this->middlewares['get'][$path] = $middleware;
			}elseif(is_string($middleware)){
				if(!in_array($middleware,array_keys($kernel))){
					echo 'No such middleware '.$middleware;
					die();
				}else{
					$this->middlewares['get'][$path] = $middleware;
				}
			}else{
				echo 'Middleware should be array or string';
				die();
			}
		} 
		$this->routes['get'][$path] = $callback;
		return $this;
	}

	public function post($path,$callback,$middleware = false){
		if($middleware){
			if(is_array($middleware)){
				foreach($middleware as $mid){
					if(!in_array($mid,array_keys($kernel))){
						echo 'No such middleware '.$mid;
						die();
					}
				}
				$this->middlewares['post'][$path] = $middleware;
			}elseif(is_string($middleware)){
				if(!in_array($middleware,array_keys($kernel))){
					echo 'No such middleware '.$middleware;
					die();
				}else{
					$this->middlewares['post'][$path] = $middleware;
				}
			}else{
				echo 'Middleware should be array or string';
				die();
			}
		}
		$this->routes['post'][$path] = $callback;
		return $this;
	}

	public function resolve(){
		$path = $this->request->getPath();
		$method = $this->request->getMethod();
		$callback = $this->routes[$method][$path] ?? false;
		$params = false;
		if($callback === false){

			foreach($this->routes[$method] as $nomur => $valik){
				$explode = explode("/",$nomur);
				$p_explode = explode("/",$path);
				foreach($explode as $key => $value){
					if(empty($explode[$key])){
						unset($explode[$key]);
					}
				}
				foreach($p_explode as $key => $value){
					if(empty($p_explode[$key])){
						unset($p_explode[$key]);
					}
				}
				if(count($explode) !== count($p_explode)){
					continue;
				}
				$sync = false;
				$basa = array_diff($p_explode,$explode);
				foreach($explode as $key => $value){
					if(isset($p_explode[$key])){
						$sync = true;
					}
				}
				foreach($basa as $key => $value){
					$nigga = $explode[$key];
					if(strpos($explode[$key],'{') === false || strpos($explode[$key],'}') === false){
						$sync = false;
					}
				}
				if(!$sync){
					continue;
				}else{
					$base = array_diff($p_explode,$explode);
					$params = [];
					foreach($base as $acar => $deyer){
						if(strpos($explode[$acar],'{') === false || strpos($explode[$acar],'}') === false){
							continue;

						}
						$replacement = str_replace("{","",$explode[$acar]);
						$replacement = str_replace("}","",$replacement);
						$params[$replacement] = $deyer;
					}
					if(!empty($params)){
						$callback = $this->routes[$method][$nomur];
					}
					continue;
				}

			}
			if($callback === false){
				$this->response->setStatusCode(404);
				echo 'Not Found';
				die();		
			}
		}
		$kernel = include __DIR__."/../middlewares/Kernel.php";
		if($this->checkAuth()){
			$at = $this->checkAuth();
		}else{
			$at = false;
		}
		if(isset($this->middlewares[$method][$path])){
			if(is_array($this->middlewares[$method][$path])){
				foreach($this->middlewares[$method][$path] as $mid){
					$path = $kernel[$mid][1];
					ob_start();
					$viewContent = file_get_contents(__DIR__."/../middlewares/$path");
					$viewContent = str_replace('auth()',var_export($at,true),$viewContent);
					eval('?>' . $viewContent);
					ob_get_clean();
					$class = new $kernel[$mid][0]();
					if(!$class->allow()){
						$this->response->setStatusCode(405);
						echo 'Method not allowed';
						die();
					}
				}
			}elseif(is_string($this->middlewares[$method][$path])){
				$mid = $this->middlewares[$method][$path];
				$path = $kernel[$mid][1];
				ob_start();
				$viewContent = file_get_contents(__DIR__."/../middlewares/$path");
				$viewContent = str_replace('auth()',var_export($at,true),$viewContent);
				eval('?>' . $viewContent);
				ob_get_clean();
				$class = new $kernel[$mid][0]();
				if(!$class->allow()){
					$this->response->setStatusCode(405);
					echo 'Method not allowed';
					die();
				}
			}
		}
		if(is_string($callback)){
			if($params){
				return $this->renderView($callback,$params);
			}else{
				return $this->renderView($callback);
			}
		}
		if(is_array($callback)){
			$callback[0] = new $callback[0]();
		}
		if($params){
			return call_user_func($callback,$params,$this->request);
		}else{
			return call_user_func($callback,$this->request);
		}
	}

	public function renderView($view,$params = []){
		$nani = false;
		$viewcontent = $this->renderOnlyView($view,$params);
		$viewcontent = str_replace('{{{','<?php echo ',$viewcontent);
		$viewcontent = str_replace('}}}',';?>',$viewcontent);
		$pattern = '/{{layout-(.*?)}}/';
		preg_match($pattern, $viewcontent, $matches);
		if(isset($matches[1])){
			$layout = $matches[1];
			$viewcontent = preg_replace($pattern, "", $viewcontent);
			$layoutcontent = $this->layoutContent($layout,$params);
			if(strpos($layoutcontent, '{{content-'.$view.'')){
				$nani = true;
				$layoutcontent =  str_replace('{{content-'.$view.'}}', $viewcontent, $layoutcontent);
			}
			$layoutcontent = str_replace('{{{','<?php',$layoutcontent);
			$layoutcontent = str_replace('}}}',';?>',$layoutcontent);
			$pattern = '/{{content-(.*?)}}/';
			$layoutcontent = preg_replace($pattern, '', $layoutcontent);
		}
		if($this->checkMessages() !== false){
			$this->unsetMessages();
		}
		if($nani){
			echo $layoutcontent;	
		}else{
			echo $viewcontent;
		}
	}

	public function returnView($view,$params = []){
		$nani = false;
		$viewcontent = $this->renderOnlyView($view,$params);
		$pattern = '/{{layout-(.*?)}}/';
		preg_match($pattern, $viewcontent, $matches);
		if(isset($matches[1])){
			$layout = $matches[1];
			$viewcontent = preg_replace($pattern, "", $viewcontent);
			$layoutcontent = $this->layoutContent($layout,$params);
			if(strpos($layoutcontent, '{{content-'.$view.'')){
				$nani = true;
				$layoutcontent =  str_replace('{{content-'.$view.'}}', $viewcontent, $layoutcontent);
			}
			$layoutcontent = str_replace('{{{','<?php',$layoutcontent);
			$layoutcontent = str_replace('}}}',';?>',$layoutcontent);
			$pattern = '/{{content-(.*?)}}/';
			$layoutcontent = preg_replace($pattern, '', $layoutcontent);
		}
		if($this->checkMessages() !== false){
			$this->unsetMessages();
		}
		if($nani){
			return $layoutcontent;	
		}else{
			return $viewcontent;
		}
	}

	public function layoutContent($layout,$params){
		if(!empty($params)){
			foreach ($params as $key => $value) {
				$$key = $value;
			}
		}
		if($this->checkMessages() !== false){
			foreach ($this->checkMessages() as $key => $value) {
				$$key = $value;
			}
		}
		if($this->checkAuth()){
			$auth = $this->checkAuth();
		}else{
			$auth = null;
		}
		ob_start();
		$viewContent = file_get_contents(__DIR__."/../views/$layout.php");

		
		$cnt = str_replace('{{{', '<?php echo ', $viewContent);
		$cnt = str_replace('}}}', '; ?>', $cnt);
		$cnt = str_replace('auth()', '$auth', $cnt);
		$cnt = str_replace('@php', '<?php', $cnt);
		$cnt = str_replace('@endphp', '?>', $cnt);
		$pattern = '/@include\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		preg_match_all($pattern, $cnt, $matches);
		$includeFiles = $matches[1];
		if(isset($includeFiles)){
			foreach($includeFiles as $fil){
				$repak = $this->renderOnlyView($fil,$params);
				$pattern = '/{{layout-(.*?)}}/';
				preg_match_all($pattern, $repak, $matches);
				if(isset($matches[1])){
					foreach($matches[1] as $mat){
						$repak = str_replace("{{layout-$mat}}","",$repak);
					}
				}
				$pattern = '/{{content-(.*?)}}/';
				preg_match_all($pattern, $repak, $matches);
				if(isset($matches[1])){
					foreach($matches[1] as $mat){
						$repak = str_replace("{{content-$mat}}","",$repak);
					}
				}
				$cnt = str_replace("@include($fil)",$repak,$cnt);
			}
		}
		$pattern = '/@isset\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		$replacement = '<?php if(isset($1)) { ?>';
		$cnt = preg_replace($pattern, $replacement, $cnt);
		$pattern = '/@error\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		$replacement = '<?php if(isset($error[$1])) { ?>';
		$cnt = preg_replace($pattern, $replacement, $cnt);
		$pattern = '/@old\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		$replacement = '<?php if(isset($old[$1])){ echo $old[$1]; } ?>';
		$cnt = preg_replace($pattern, $replacement, $cnt);
		$pattern = '/@foreach\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		$replacement = '<?php foreach($1) { ?>';
		$cnt = preg_replace($pattern, $replacement, $cnt);
		$cnt = str_replace('@endforeach', '<?php } ?>', $cnt);
		$pattern = '/@if\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		$replacement = '<?php if($1) { ?>';
		$cnt = preg_replace($pattern, $replacement, $cnt);
		$pattern = '/@elseif\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		$replacement = '<?php }elseif($1) { ?>';
		$cnt = preg_replace($pattern, $replacement, $cnt);
		$cnt = str_replace('@else', '<?php }else{ ?>', $cnt);
		if(!$this->checkAuth()){
			$pattern = '/@auth.*?@endauth\s*/s';
			$cnt = preg_replace($pattern, '', $cnt);
			$cnt = str_replace('@guest','',$cnt);
			$cnt = str_replace('@endguest','',$cnt);
		}else{
			$pattern = '/@guest.*?@endguest\s*/s';
			$cnt = preg_replace($pattern, '', $cnt);
			$cnt = str_replace('@auth','',$cnt);
			$cnt = str_replace('@endauth','',$cnt);
		}
		$cnt = str_replace('@end', '<?php } ?>', $cnt);
		eval('?>' . $cnt);
		return ob_get_clean();
	}

	public function renderOnlyView($view,$params){
		if(!empty($params)){
			foreach ($params as $key => $value) {
				$$key = $value;
			}
		}
		if($this->checkMessages() !== false){
			foreach ($this->checkMessages() as $key => $value) {
				$$key = $value;
			}
		}
		if($this->checkAuth()){
			$auth = $this->checkAuth();
		}else{
			$auth = null;
		}
		ob_start();
		$viewContent = file_get_contents(__DIR__."/../views/$view.php");

		
		$cnt = str_replace('{{{', '<?php echo ', $viewContent);
		$cnt = str_replace('}}}', '; ?>', $cnt);
		$cnt = str_replace('auth()', '$auth', $cnt);
		$cnt = str_replace('@php', '<?php', $cnt);
		$cnt = str_replace('@endphp', '?>', $cnt);
		$pattern = '/@include\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		preg_match_all($pattern, $cnt, $matches);
		$includeFiles = $matches[1];
		if(isset($includeFiles)){
			foreach($includeFiles as $fil){
				$repak = $this->renderOnlyView($fil,$params);
				$pattern = '/{{layout-(.*?)}}/';
				preg_match_all($pattern, $repak, $matches);
				if(isset($matches[1])){
					foreach($matches[1] as $mat){
						$repak = str_replace("{{layout-$mat}}","",$repak);
					}
				}
				$pattern = '/{{content-(.*?)}}/';
				preg_match_all($pattern, $repak, $matches);
				if(isset($matches[1])){
					foreach($matches[1] as $mat){
						$repak = str_replace("{{content-$mat}}","",$repak);
					}
				}
				$cnt = str_replace("@include($fil)",$repak,$cnt);
			}
		}
		$pattern = '/@isset\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		$replacement = '<?php if(isset($1)) { ?>';
		$cnt = preg_replace($pattern, $replacement, $cnt);
		$pattern = '/@error\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		$replacement = '<?php if(isset($error[$1])) { ?>';
		$cnt = preg_replace($pattern, $replacement, $cnt);
		$pattern = '/@old\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		$replacement = '<?php if(isset($old[$1])){ echo $old[$1]; } ?>';
		$cnt = preg_replace($pattern, $replacement, $cnt);
		$pattern = '/@foreach\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		$replacement = '<?php foreach($1) { ?>';
		$cnt = preg_replace($pattern, $replacement, $cnt);
		$cnt = str_replace('@endforeach', '<?php } ?>', $cnt);
		$pattern = '/@if\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		$replacement = '<?php if($1) { ?>';
		$cnt = preg_replace($pattern, $replacement, $cnt);
		$pattern = '/@elseif\(([^()]*+(?:\((?1)\)[^()]*)*+)\)/s';
		$replacement = '<?php }elseif($1) { ?>';
		$cnt = preg_replace($pattern, $replacement, $cnt);
		$cnt = str_replace('@else', '<?php }else{ ?>', $cnt);
		if(!$this->checkAuth()){
			$pattern = '/@auth.*?@endauth\s*/s';
			$cnt = preg_replace($pattern, '', $cnt);
			$cnt = str_replace('@guest','',$cnt);
			$cnt = str_replace('@endguest','',$cnt);
		}else{
			$pattern = '/@guest.*?@endguest\s*/s';
			$cnt = preg_replace($pattern, '', $cnt);
			$cnt = str_replace('@auth','',$cnt);
			$cnt = str_replace('@endauth','',$cnt);
		}
		$cnt = str_replace('@end', '<?php } ?>', $cnt);
		eval('?>' . $cnt);
		return ob_get_clean();
	}

	public function checkAuth(){
		if(Session::get('USER')){
			$user = Session::get('USER');
			$db = mysqli_connect('localhost',Application::$env['DB_USER'],Application::$env['DB_PASSWORD'],Application::$env['DB_NAME']);
			$query = mysqli_query($db,"SELECT * FROM users WHERE email = '".$user->email."' AND password = '".$user->password."' ");
			if (mysqli_num_rows($query) > 0) {
			    return $user;
			} else {
			    Session::remove('USER');
			    return false;
			}
		}else{ 
			return false;
		}
	}

	public function checkMessages(){
		if(Session::get('REDIRECT_MESSAGES')){
			return Session::get('REDIRECT_MESSAGES');
		}else{
			return false;
		}
	}
	
	public function unsetMessages(){
		if(Session::get('REDIRECT_MESSAGES')){
			Session::remove('REDIRECT_MESSAGES');
		}
	}


}

?>
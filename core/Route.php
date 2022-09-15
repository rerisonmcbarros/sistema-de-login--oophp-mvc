<?php

namespace Core;

class Route{
	
	/** @var array */
	private $routes;
	/** @var string */
	private $path;
	/** @var string */
	private $needle;
	/** @var string */
	private $error;
	/** @var string */
	private $namespace;

	public function __construct(string $path, string $needle){

		$this->needle= $needle;
		$this->path= $path;

	}

	/** @param $route string */
	/** @param $function object */
	/** @return object */
	public function get(string $route, $function){

		$method="GET";

		$controller = mb_strstr($function, "$this->needle", true);
		$action = mb_substr(mb_strstr($function,"$this->needle"), 1);

		$dataRoute = ["controller" => $controller, "action" => $action];


		$this->addRoute($method, $route, $dataRoute);

		return $this;

	}

	/** @param $route string */
	/** @param $function object */
	/** @return object */
	public function post(string $route, $function){

		$method="POST";

		$controller = mb_strstr($function, "$this->needle", true);
		$action = mb_substr(mb_strstr($function,"$this->needle"), 1);

		$dataRoute = ["controller" => $controller, "action" => $action];


		$this->addRoute($method, $route, $dataRoute);
		return $this;
	}

	/** @param $route string */
	/** @param $function object */
	public function addRoute(string $method, $route, $data){

		$routeExplode = explode("/", $route);

		$countRoute = count($routeExplode);

		for ($i=0; $i < $countRoute ; $i++) { 
				
			
			$result = preg_match("/{(.*)}/", $routeExplode[$i]);

			if($result){

				$routeExplode[$i]= preg_replace("/{(.*)}/", "(.*?)", $routeExplode[$i]);
			
			}

		}
		
		$route = implode("/", $routeExplode);

		$route = str_replace("/", "\/", $route);

		$this->routes[$method][$route]=$data;

	}

	/** @return null|Exception */
	public function dispatch(){

		$method = $_SERVER["REQUEST_METHOD"];

		$uri = $this->getUri();

		$explodeUri = explode("/", $uri);

		$countUri = count($explodeUri);


		try{

			foreach ($this->routes[$method] as $route => $value) {

				$explodeRoute = explode("/", $route);

				$countRoute = count($explodeRoute);

				$pattern ="/^{$route}$/";

				$result = preg_match($pattern, $uri, $matches);

				array_shift($matches);

				$params = ($matches != null ? $matches : []);

				if($result == 1 && $countRoute == $countUri){

					$function = $this->routes[$method][$route];

					$controller = "\\".$this->namespace."\\".$function["controller"];
					$action = $function["action"];

					$controller = new $controller;

					call_user_func_array([$controller,$action], $params);

					return;

				}

			}

			throw new \Exception("Error not Found", 404);

		}catch(\Exception $e){	
			
			http_response_code($e->getCode());

			$this->error["message"]= $e->getMessage();

		}
	}

	/** @return string */
	public function getUri(){

			$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

			$prefix = $this->path;
			if(mb_strpos($uri, $prefix) !== false){

				$prefixLen = mb_strlen($this->path);
				$uri = mb_substr($uri, $prefixLen);
			}
		
		return $uri;
	}

	/** @param string */
	public function setNamespace(string $namespace){

		$this->namespace = $namespace;

	}


	/** @return string */
	public function error(){

		return $this->error;

	}

}
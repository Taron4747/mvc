<?php

namespace App\Core;


/**
* @package App\Core;
 * 
 */

class Router 
{
	
	public function __construct(Request $request,Response $response)
	{
		$this->request =$request;
		$this->response =$response;
	}
	protected  $routes =[];
	public function get($path,$callback)
	{
		$this->routes['get'][$path] =$callback;
	}
	public function post($path,$callback)
	{
		$this->routes['post'][$path] =$callback;
	}
	public function resolve()
	{
		
		$path = $this->request->getPath();
		$method =$this->request->method();
		$callback =$this->routes[$method][$path] ?? false;
		if ($callback === false) {
			$this->response->setStatusCode(404);
			return $this->renderView('404') ;
		}
		if (is_string($callback)) {
			return $this->renderView($callback);
		} 
		if (is_array($callback)) {
			$callback[0] =new $callback[0]();
		}
		return call_user_func($callback,$this->request);
	}

	public function renderView($view,$params=[])
	{
		$layoutContent =$this->layoutContent();
		$viewContent=$this->renderOnlyView($view,$params);
		return str_replace('{{content}}',$viewContent, $layoutContent);
	}
	public function renderContent($viewContent)
	{
		$layoutContent =$this->layoutContent();
		return str_replace('{{content}}',$viewContent, $layoutContent);
	}
	public function layoutContent($value='')
	{
		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true) {
			$loggedin =true;

		}else{
			$loggedin =false;
		}
		ob_start();
		include_once Application::$ROOT_DIR."/Views/Layouts/mainLayout.php";
		return ob_get_clean();
	}
	protected function renderOnlyView($view,$params)
	{
		foreach ($params as $key => $param) {
			$$key=$param;
		}
		ob_start();
		include_once Application::$ROOT_DIR."/Views/$view.php";
		return ob_get_clean();
	}
}
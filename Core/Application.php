<?php
namespace App\Core;
use App\Core\Router;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;


/**
* @package App\Core;
 * 
 */



class Application 
{
	public static  $ROOT_DIR;
	public static  $app;
	public function __construct($rootPath,$config)
	{
		self::$ROOT_DIR =$rootPath;
		self::$app =$this;
		$this->request = new Request();
		$this->response = new Response();
		$this->router = new Router($this->request,$this->response );
		$this->db = new Database($config['db']);

	}

	public function run($value='')
	{
		echo $this->router->resolve();
	}
}
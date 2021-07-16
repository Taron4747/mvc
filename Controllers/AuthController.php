<?php
namespace App\Controllers;
use App\Core\Application;
use App\Core\Request;

/**
 * 
 */
class AuthController
{ 
	

	public static function getLogin($value='')
	{
			$params =[
			'error'=>''
			];
		return Application::$app->router->renderView('login',$params);
		
	}
	public static function postLogin($value='')
	{

		$login =$_POST['name'];
		$password =$_POST['password'];
		$sql = "SELECT name  from users where name=? and password=? ";
		$statement = Application::$app->db->pdo->prepare($sql);
		$statement->bindParam(1, $login, \PDO::PARAM_STR);
		$statement->bindParam(2, $password, \PDO::PARAM_STR);
		$records = $statement->execute();
		$result = $statement->fetch(\PDO::FETCH_ASSOC);
		if ($result) {
			$_SESSION['loggedin']=true;
			header('Location: /');

		}else{
			$params =[
			'error'=>'Wrong credentials'	
			];
			return Application::$app->router->renderView('login',$params);

		}
	}
	public function getLogout(Request $request)
	{
		
		$_SESSION['loggedin']=false;
		header('Location: /');
		
	}
}
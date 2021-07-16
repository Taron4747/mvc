<?php
use App\Controllers\TasksController;
use App\Controllers\AuthController;
use App\Core\Application;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


$config =[
	'db'=>[
		'dsn'=>$_ENV['DB_DSN'],
		'user'=>$_ENV['DB_USER'],
		'password'=>$_ENV['DB_PASSWORD']
	]
];
$app = new Application(dirname(__DIR__),$config);

		session_start();

$app->router->get('/',[TasksController::class,'getTasks']);
$app->router->get('/tasks',[TasksController::class,'getTasks']);
$app->router->post('/get-tasks',[TasksController::class,'getTasksData']);
$app->router->get('/create-task',[TasksController::class,'tasks']);
$app->router->post('/create-task',[TasksController::class,'postCreateTask']);
$app->router->post('/edit-task',[TasksController::class,'postEditTask']);
$app->router->post('/change-status',[TasksController::class,'postChangeStatus']);

$app->router->get('/login',[AuthController::class,'getLogin']);
$app->router->post('/login',[AuthController::class,'postLogin']);
$app->router->get('/logout',[AuthController::class,'getLogout']);
$app->router->post('/logout',[AuthController::class,'postLogout']);

$app->run();
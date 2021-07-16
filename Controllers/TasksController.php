<?php
namespace App\Controllers;
use App\Core\Application;
use App\Core\Request;
use App\Models\Tasks;

/**
 * 
 */
class TasksController
{ 
	
	public function getTasks($value='')
	{

		if (isset($_SESSION['task_created'])) {
			$task_created ="Task created succesfuly";;
			unset($_SESSION['task_created']);

		}else{
			$task_created ='';
		}
		if (isset($_SESSION['task_edited'])) {
			$task_edited ="Task edited succesfuly";;
			unset($_SESSION['task_edited']);

		}else{
			$task_edited ='';
		}
		if (isset($_SESSION['loggedin']) &&$_SESSION['loggedin']==true) {
			$loggedin =true;
			   $columns = [
                    [ "data"=> "name","title"=> "Name"],
                    [ "data"=> "email" ,"title"=>"Email"],
                    [ "data"=> "task" ,"title"=>"Task"],
                    [ "data"=> "status","title"=>"Status" ],
                    [ "data"=> "is_edited","title"=>"Edited" ],
                    [ "data"=> "edit","title"=>"Edit" ],   
                ];

		}else{
			$loggedin =false;
			  $columns = [
                    [ "data"=> "name","title"=> "Name"],
                    [ "data"=> "email" ,"title"=>"Email"],
                    [ "data"=> "task" ,"title"=>"Task"],
                 
                    
                ];
		}
		$params =[
			'model'=>new Tasks(),
			'task_created'=>$task_created,
			'task_edited'=>$task_edited,
			'loggedin'=>$loggedin,
			'columns'=>json_encode($columns,true),
		];
		return Application::$app->router->renderView('get-tasks',$params);
	}
	public function getTasksData($value='')
	{
		$draw = $_POST['draw'];
		$row =  (int)trim($_POST['start']);
		$rowperpage =  (int)trim($_POST['length']); 
		$columnIndex = $_POST['order'][0]['column']; 
		$columnName = $_POST['columns'][$columnIndex]['data'];
		$columnSortOrder = $_POST['order'][0]['dir'];
		$sql = "SELECT count(*) as allcount from tasks";
		$statement = Application::$app->db->pdo->prepare($sql);
		$records = $statement->execute();
		$result = $statement->fetch(\PDO::FETCH_ASSOC);
		$totalRecords = $result['allcount'];
		$sql = "select id,name,email,task,is_done,is_edited from tasks order by ? ? limit ?,?";
		$statement = Application::$app->db->pdo->prepare($sql);
		$statement->bindParam(1, $columnName, \PDO::PARAM_STR);
		$statement->bindParam(2, $columnSortOrder, \PDO::PARAM_STR);
		$statement->bindParam(3,  $row, \PDO::PARAM_INT);
		$statement->bindParam(4,  $rowperpage, \PDO::PARAM_INT);
		$records = $statement->execute();
		$result = $statement->fetchAll(\PDO::FETCH_ASSOC);

		if (isset($_SESSION['loggedin']) &&$_SESSION['loggedin']==true) {
			foreach ($result as $key => $value) {
				$checkbox ='<div class="form-check">
					<input class="form-check-input is-done" data-id="'.$value['id'].'"  type="checkbox" value="0" >
					<label class="form-check-label"   for="defaultCheck1">   
					</label>
					</div>';
				$checkedCheckbox ='<div class="form-check">
		  			<input class="form-check-input is-done" data-id="'.$value['id'].'" type="checkbox" checked="checked" value="1" >
		  			<label class="form-check-label"  for="defaultCheck1">
		  			</label>
					</div>';
				$result[$key]['status']=$value['is_done'] ? $checkedCheckbox : $checkbox;
				$result[$key]['is_edited']=$value['is_edited'] ? 'Yes' : 'No';
				$result[$key]['edit']='<button type="button" class="btn btn-primary edit-button" data-toggle="modal" data-target="#editModal"  data-name="'.$value['name'].'" data-id="'.$value['id'].'" data-email="'.$value['email'].'" data-task="'.$value['task'].'">Edit</button>';
			}
		}

		$response = array(
		  "draw" => intval($draw),
		  "iTotalRecords" => $totalRecords,
		  "iTotalDisplayRecords" =>  $totalRecords,
		  "aaData" => $result
		);

		echo json_encode($response);
	}
	public static function tasks($value='')
	{
		$params =[
			'model'=>new Tasks()
		];
		return Application::$app->router->renderView('create-task',$params);
	}
	public function postCreateTask(Request $request)
	{
		$body = $request->getBody();
		$taskModel = new Tasks();
		$taskModel->loadData($body);
		if (empty($taskModel->validate())  && $taskModel->createTask($body)) {
			session_start();
			$_SESSION["task_created"] = true;
			header('Location: /');
		}
		return Application::$app->router->renderView('create-task',['model'=>$taskModel]);
	}

	public function postEditTask(Request $request)
	{
		
		$body = $request->getBody();
		$taskModel = new Tasks();
		$taskModel->loadData($body);
		if (empty($taskModel->validate())  && $taskModel->editTask($body)) {
			$_SESSION["task_edited"] = true;
			return $this->getTasks();
		}

		return $this->getTasks();
	}
	public function postChangeStatus(Request $request)
	{
		$body = $request->getBody();
		if ($body['checked']=='true') {
			$body['checked']=1;
		}else{
			$body['checked']=0;
		}
		$taskModel = new Tasks();
		$taskModel->loadData($body);
		$taskModel->changeStatus($body);
		echo json_encode(array("message"=>'Task status changed succesfuly'));
	}
}
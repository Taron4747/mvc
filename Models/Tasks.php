<?php

namespace App\Models;
use App\Core\Application;

/**
 * 
 */
class Tasks  
{
	public  $name;
	public  $email;
	public  $task;
	public 	$error =[];
	public function loadData($data)
	{
		foreach ($data as $key => $value) {
			if (property_exists($this, $key)) {
				$this->{$key}=$value;
			}
		}
	}

	public function validate()
	{
		$error=[];
		foreach ($this->rules() as $attribute => $rules) {
			$value = $this->{$attribute};
			foreach ($rules as  $rule) {
				$ruleName=$rule;
				if (!is_string($ruleName)) {
					$ruleName = $rule[0];
				}
				if ($ruleName =='required'&& !$value && $value =='') {
					$error[$attribute] = $this->addError($attribute,'required');
				}
				if ($ruleName =='email'&& !filter_var( $value,FILTER_VALIDATE_EMAIL)) {
					$error[$attribute] = $this->addError($attribute,'email');
				}
			}
		}
		 $this->error = $error;
		return $error ;
	}
	public function addError($attribute,$rule)
	{
		$message =$this->errorMessages()[$rule] ?? '';
		return $message;
	}
	public function errorMessages($value='')
	{
		return [
		'required'=>'This field is required',
		'email'=>'This field must be valid email',
		];
	}
	public function createTask($data)
	{
	
		$statement = self::prepare("INSERT INTO tasks (name,email,task) VALUES(?,?,?)");
		$statement->bindParam(1, $data['name'], \PDO::PARAM_STR);
		$statement->bindParam(2, $data['email'], \PDO::PARAM_STR);
		$statement->bindParam(3, $data['task'], \PDO::PARAM_STR);
		$statement->execute();
		return true;
	}
	public function editTask($data)
	{
		$statement = self::prepare("UPDATE tasks
			SET name = ?, email= ? ,task =?, is_edited =?
			WHERE id = ?;");
		$is_edited =1;
		$statement->bindParam(1, $data['name'], \PDO::PARAM_STR);
		$statement->bindParam(2, $data['email'], \PDO::PARAM_STR);
		$statement->bindParam(3, $data['task'], \PDO::PARAM_STR);
		$statement->bindParam(4, $is_edited, \PDO::PARAM_INT);
		$statement->bindParam(5, $data['id'], \PDO::PARAM_INT);
		$statement->execute();
		return true;

	}
	public function changeStatus($data)
	{
		$statement = self::prepare("UPDATE tasks
		SET is_done = ?
		WHERE id = ?;");
		$statement->bindParam(1,$data['checked'] , \PDO::PARAM_STR);
		$statement->bindParam(2, $data['id'], \PDO::PARAM_STR);
		
		$statement->execute();
		return true;
		
	}
	public function rules():array
	{
		return [
			'name'=>['required'],
			'task'=>['required'],
			'email'=>['required','email']
		];
	}

	public function hasError($attribute)
	{
		if ($this->error) {
			return $this->error[$attribute] ?? false;
		}
	}
	public static  function prepare($sql)
	{
		return Application::$app->db->pdo->prepare($sql);
	}
}
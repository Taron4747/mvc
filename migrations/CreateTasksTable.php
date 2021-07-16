<?php
/**
 * 
 */
class CreateTasksTable 
{
	public function up()
	{
		$db = \App\Core\Application::$app->db;
		$sql = 'CREATE TABLE tasks (
			  id INT AUTO_INCREMENT PRIMARY KEY,
			  name VARCHAR(50),
			  email VARCHAR(50),
			  task VARCHAR(255),
			  is_done INT DEFAULT 0,
			  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
			) ENGINE = INNODB;';
		$db->pdo->exec($sql);
	}

	public function down()
	{
		# code...
	}
	 
}

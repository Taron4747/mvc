<?php
/**
 * 
 */
class CreateUsersTable 
{
	public function up()
	{
		$db = \App\Core\Application::$app->db;
		$sql = 'CREATE TABLE users (
			  id INT AUTO_INCREMENT PRIMARY KEY,
			  name VARCHAR(50),
			  email VARCHAR(50),
			  password VARCHAR(50),

			  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
			) ENGINE = INNODB;';
		$db->pdo->exec($sql);
	}

	public function down()
	{
		# code...
	}
	 
}

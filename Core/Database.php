<?php
namespace App\Core;
use App\Core\Application;

/**
 * 
 */
class Database 
{
	
	function __construct($config)
	{
		$dsn = $config['dsn'] ?? '';
		$user = $config['user'] ?? '';
		$password = $config['password'] ?? '';
		$this->pdo = new\PDO($dsn,$user,$password);
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION) ;
	}


	public function applyMigrations()
	{
		var_dump(Application::$ROOT_DIR);
		$this->createMogrationsTable();
		$appliedMigrations = $this->getAppliedMigrations();
		$newMigrations =[];
		$files = scandir(Application::$ROOT_DIR.'/migrations');
		$toApplyMigrations = array_diff($files, $appliedMigrations);
		foreach ($toApplyMigrations as $toApplyMigration) {
			if ($toApplyMigration ==='.'||$toApplyMigration ==='..' ) {
				continue;
			}
			require_once  Application::$ROOT_DIR.'/migrations/'.$toApplyMigration;
			$className = pathinfo($toApplyMigration,PATHINFO_FILENAME);
			$instance = new $className();
			echo "Applying migration $toApplyMigration".PHP_EOL;
			$instance ->up();
			echo "Applied migration $toApplyMigration".PHP_EOL;
			$newMigrations[]=$toApplyMigration;
			if (!empty($newMigrations)) {
				$this->saveMigrations($newMigrations);
			}else{
				echo "All migrations rae applied";
			}

		}
	}

	public function createMogrationsTable($value='')
	{
		$this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
		  id INT AUTO_INCREMENT PRIMARY KEY,
		  migration VARCHAR(255),
		  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		) ENGINE = INNODB;");
	}
	public function getAppliedMigrations()
	{
		$statement =$this->pdo->prepare('SELECT migration FROM migrations');
		$statement->execute();
		return $statement->fetchAll(\PDO::FETCH_COLUMN);

	}

	public function saveMigrations($migrations)
	{

		$str = implode("," , array_map(fn($m) , "('$m')" , $migrations ));
		$statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
		$statement->execute();
	}
}
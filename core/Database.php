<?php

namespace app\core;

use app\migrations\Jahrein;

class Database{
	public \PDO $pdo;

	public function __construct($cfg){

		$dsn = $cfg['dsn'];
		$user = $cfg['user'];
		$password = $cfg['password'];

		$this->pdo = new \PDO($dsn,$user,$password);
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
	}

	public function applyMigrations(){
		$this->createMigrations();
		$appliedMigrations = $this->getAppliedMigrations();

		$newmigrations = [];

		$files = scandir(__DIR__."/../migrations");
		$applying = array_diff($files, $appliedMigrations);
		foreach($applying as $ahh){
			if($ahh === '.' || $ahh === '..'){
				continue;
			}
			$migration = include __DIR__."/../migrations/$ahh";
			$migration->up();
			unset($migration);
			$newmigrations[] = $ahh;
		}

		if(!empty($newmigrations)){
			$this->saveMigrations($newmigrations);
		}
		$oldmigrations = array_diff($appliedMigrations,$files);
		if(!empty($oldmigrations)){
			$this->deleteMigrations($oldmigrations);
		}
	}

	public function createMigrations(){
		$this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
			id INT AUTO_INCREMENT PRIMARY KEY,
			migration VARCHAR(255),
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		) ENGINE=INNODB;");
	}

	public function getAppliedMigrations(){
		$query = $this->pdo->prepare("SELECT migration FROM migrations");
		$query->execute();

		return $query->fetchAll(\PDO::FETCH_COLUMN);
	}

	public function saveMigrations($migs){
		$migrations = implode(",", array_map(fn($m)=> "('$m')",$migs));
		$query = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES
			$migrations
		");
		$query->execute();
	}

	public function deleteMigrations($migs){
		foreach($migs as $amigo){
			$this->pdo->exec("DELETE FROM migrations WHERE migration = '".$amigo."' ");
		}
	}
	public function dropAll(){
		$tablesQuery = "SHOW TABLES";
	    $tablesResult = $this->pdo->query($tablesQuery);
	    if ($tablesResult) {
	        while ($row = $tablesResult->fetch(\PDO::FETCH_NUM)) {
	            $tableName = $row[0];
	            $dropQuery = "DROP TABLE IF EXISTS `$tableName`";
	            $this->pdo->exec($dropQuery);
	            echo "Dropped table: $tableName" . PHP_EOL;
	        }
	    } else {
	        echo "Error retrieving table list: " . $this->pdo->errorInfo()[2] . PHP_EOL;
	    }
	}
}

?>
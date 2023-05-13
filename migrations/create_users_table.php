<?php

use app\core\Application;
use app\core\Table;

return new class extends Table{

	public function up(){
		$this->tableName('users');
		$this->id();
		$this->string('name')->default('something');
		$this->string('email');
		$this->string('password');
		$this->integer('age',3)->default(18)->nullable();
		$this->timestamp();
		$this->create();
	}

	public function down(){
		$this->drop('users');
	}

}

?>
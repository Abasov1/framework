<?php

$date = date('Y_m_d_H_i_s');

$filename = "migrations/c_$date.php";
$filecontent = '<?php

use app\core\Application;
use app\core\Table;

return new class extends Table{

	public function up(){
		$this->tableName("something");
		$this->id();
		
		$this->timestamp();
		$this->create();
	}

	public function down(){
		$this->drop("something");
	}

}

?>';

$file = fopen($filename, 'w');

fwrite($file, $filecontent);

fclose($file);

if (file_exists($filename)) {
    echo "c_$date.php created successfully.";
} else {
    echo "Failed to create the c_$date.php .";
}

?>

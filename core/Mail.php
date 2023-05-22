<?php 

namespace app\core;

require_once __DIR__.'/../vendor/autoload.php';

use app\core\Application;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


class Mail{

	public static function send($to,$view,$params = [],$subject = "Your Subject"){
		$app = new Application($_ENV);
		$env = Application::$env;
		$mail = new PHPMailer(true);
		$mail->isSMTP();                                            
		$mail->Host = $env['MAIL_HOST'];                     
		$mail->SMTPAuth = true;                                   
		$mail->Username = $env['MAIL_USERNAME'];                     
		$mail->Password = $env['MAIL_PASSWORD'];                               
		$mail->SMTPSecure = $env['MAIL_SMTP'];            
		$mail->Port = $env['MAIL_PORT'];                                    
		$mail->addAddress($to);
		$mail->Subject = $subject;
		ob_start();
		eval('?>' . $app->router->returnView($view,$params) . '<?php ');
		$evaluatedOutput = ob_get_clean();
		$mail->isHTML(true); 
		$mail->Body = $evaluatedOutput;
		$mail->send();
	}

}
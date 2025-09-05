<?php 

include("PHPMailer/PHPMailer.php");
include("PHPMailer/Exception.php");
include("PHPMailer/SMTP.php");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class PHPMailerCI
{
	public $mail;
	public function __construct($arg=array())
	{
		
		$this->mail = new PHPMailer(true);
		$this->mail->CharSet = "UTF-8";

		//Server settings
		$this->mail->SMTPDebug  = 0;                                       
		$this->mail->isSMTP();                                            
		$this->mail->Host       = $arg["host"]; 
		$this->mail->SMTPAuth   = true; 
		$this->mail->Username   = $arg["username"];
		$this->mail->Password   = $arg["password"];
		//$this->mail->SMTPSecure = false;//$arg["smtp_secure"]; 
		//$this->mail->SMTPAutoTLS = false;
		$this->mail->Port       = $arg["port"];
		
	}
	public function setFrom($mailfrom,$namefrom){
		$this->mail->setFrom($mailfrom, $namefrom);
	}
	public function AddReplyTo($mailfrom,$namefrom){
		$this->mail->AddReplyTo($mailfrom, $namefrom);
	}
	public function addStringAttachment($file,$name){
		$this->mail->addStringAttachment(file_get_contents($file), $name.'.pdf');
	}
	function send_mail($to,$subject,$body,&$message=""){
	

		try {
    $this->mail->ClearAllRecipients(); 
    $this->mail->addAddress($to);     // Add a recipient

    $this->mail->isHTML(true);                             // Set email format to HTML

	$this->mail->Priority = 1;
	 
	$this->mail->AddCustomHeader("X-MSMail-Priority: High");
 
	$this->mail->AddCustomHeader("Importance: High");
   
    $this->mail->Subject = $subject;
    $this->mail->Body    = $body;


    $this->mail->send();
    return true;
} catch (Exception $e) {
	$message= "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
	return false;
}
}

}
?>
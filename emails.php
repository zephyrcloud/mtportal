<?php

include("config/PHPMailer-master/PHPMailerAutoload.php");
include("config/connection.php");
include("config/ip_capture.php");

class emails{



function enviar_correo($subject,$body_message) {

       $mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'jbriceno@zephyrcloud.com';                 // SMTP username
		$mail->Password = 'Zcc12345';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to

		$mail->setFrom('jbriceno@zephyrcloud.com', 'Mailer');
		$mail->addAddress('jbriceno@zephyrcloud.com');               // Name is optional
		
		$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = $subject;
		$mail->Body    = $body_message;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		$mail->send();
		
		/*if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent';
		}*/
}

function body_email($message,$ip,$action_type,$result, $table_modified, $id_user){
		$subject = $message;
		$body_message= $message." from IP address: ". $ip. "</b>";
		
		$select_apps_query = 'SELECT `id`, `action_name` FROM `action_type` WHERE `id` = '.$action_type;
		$select_apps_result = mysql_query($select_apps_query) or die('1 ' . mysql_error());
		
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			$body_message.= "<br> The action type was:  <b>".$line['action_name']. "</b>";
		}	
		
		$select_apps_query = 'SELECT `id`, `result_name` FROM `result` WHERE `id` = '.$result;
		$select_apps_result = mysql_query($select_apps_query) or die('2 ' . mysql_error());
		
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			$body_message.= "<br> The action type was:  <b> ".$line['result_name']. "</b>";
		}
		
		$select_apps_query = 'SELECT `id`, `table_name` FROM `table_modified` WHERE `id` = '.$table_modified;
		$select_apps_result = mysql_query($select_apps_query) or die('3 ' . mysql_error());
		
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			$body_message.= "<br> The table modified was:  <b> ".$line['table_name']. "</b>";
		}
		
		$select_apps_query = 'SELECT  `username` FROM `customer` WHERE `id` = '.$id_user;
		$select_apps_result = mysql_query($select_apps_query) or die('4 '. $message . " - " . mysql_error());
		
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			$body_message.= "<br> The customer was:  <b>".$line['username'] . "</b>";
		}
		// here ends the body of the event for email send
		$this-> enviar_correo($subject,$body_message);
}

function body_email_cus($message,$ip,$action_type,$result, $table_modified, $id_user){
		$subject = $message;
		$body_message= $message." from IP address: ". $ip. "</b>";
		
		$select_apps_query = 'SELECT `id`, `action_name` FROM `action_type` WHERE `id` = '.$action_type;
		$select_apps_result = mysql_query($select_apps_query) or die('1 ' . mysql_error());
		
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			$body_message.= "<br> The action type was:  <b>".$line['action_name']. "</b>";
		}	
		
		$select_apps_query = 'SELECT `id`, `result_name` FROM `result` WHERE `id` = '.$result;
		$select_apps_result = mysql_query($select_apps_query) or die('2 ' . mysql_error());
		
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			$body_message.= "<br> The action type was:  <b> ".$line['result_name']. "</b>";
		}
		
		$select_apps_query = 'SELECT `id`, `table_name` FROM `table_modified` WHERE `id` = '.$table_modified;
		$select_apps_result = mysql_query($select_apps_query) or die('3 ' . mysql_error());
		
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			$body_message.= "<br> The table modified was:  <b> ".$line['table_name']. "</b>";
		}
		
		$select_apps_query = 'SELECT CONCAT(u.`firstName`," ",u.`lastName`) as nameuser , c.name  as namecustomer FROM `user` u, customer c WHERE c.id = u.customer AND u.`id` = '.$id_user;
		$select_apps_result = mysql_query($select_apps_query) or die('4 '  .mysql_error());
		
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			$body_message.= "<br> The user  was:  <b>".$line['nameuser'] . "</b>";
			$body_message.= "<br> From the custmore:  <b>".$line['namecustomer'] . "</b>";
		}
		// here ends the body of the event for email send
		$this-> enviar_correo($subject,$body_message);
}

function body_email_apps($message,$ip,$action_type,$result, $table_modified, $id_user,$selected,$not_selected){
		$subject = $message;
		$body_message= $message." from IP address: ". $ip. "</b>";
		
		$select_apps_query = 'SELECT `id`, `action_name` FROM `action_type` WHERE `id` = '.$action_type;
		$select_apps_result = mysql_query($select_apps_query) or die('1 ' . mysql_error());
		
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			$body_message.= "<br> The action type was:  <b>".$line['action_name']. "</b>";
		}	
		
		$select_apps_query = 'SELECT `id`, `result_name` FROM `result` WHERE `id` = '.$result;
		$select_apps_result = mysql_query($select_apps_query) or die('2 ' . mysql_error());
		
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			$body_message.= "<br> The action type was:  <b> ".$line['result_name']. "</b>";
		}
		
		$select_apps_query = 'SELECT `id`, `table_name` FROM `table_modified` WHERE `id` = '.$table_modified;
		$select_apps_result = mysql_query($select_apps_query) or die('3 ' . mysql_error());
		
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			$body_message.= "<br> The table modified was:  <b> ".$line['table_name']. "</b>";
		}
		
		$select_apps_query = 'SELECT  `username` FROM `customer` WHERE `id` = '.$id_user;
		$select_apps_result = mysql_query($select_apps_query) or die('4 '. $message . " - " . mysql_error());
		
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			$body_message.= "<br> The customer was:  <b>".$line['username'] . "</b>";
		}
		
		$select_apps_query = 'SELECT  `username` FROM `customer` WHERE `id` = '.$id_user;
		$select_apps_result = mysql_query($select_apps_query) or die('4 '. $message . " - " . mysql_error());
		
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			$body_message.= "<br> The customer was:  <b>".$line['username'] . "</b>";
		}
		$body_message.= "<br> It has checked:  <b>".$selected . "</b>";
		$body_message.= "<br> Check removed:  <b>".$not_selected . "</b>";
		// here ends the body of the event for email send
		$this-> enviar_correo($subject,$body_message);
}

}
	
?>


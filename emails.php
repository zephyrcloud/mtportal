<?php

include("config/PHPMailer-master/PHPMailerAutoload.php");
include("config/connection.php");
include("config/ip_capture.php");

class emails{



function send_email($subject,$body_message) {

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

function body_email($message,$logout_time_out,$user){
		
		
		if($logout_time_out == "" || $logout_time_out == null ){
			$logout_time_out = "unknown";
		}
		
		// it finds the maximun time of the user when he/her did login
		//SELECT max(`timeStamp`) as last_log FROM `log` WHERE `id_actionType` = 1 and `id_user` = ".$_POST["id_user"]."
		$select_customers_query = 'SELECT max(`timeStamp`) as last_log FROM `log` WHERE `id_actionType` = 1 and `id_user` = '.$user;
		$select_customers_result = mysql_query($select_customers_query) or die('Consulta fallida: ' . mysql_error());
		while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				$last_login= $line['last_log'];
		}
		
		$select_customers_query = 'SELECT `name` FROM `customer` WHERE `id` ='.$user;
		$select_customers_result = mysql_query($select_customers_query) or die('Consulta fallida: ' . mysql_error());
		while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				$user_name= $line['name'];
		}
		// it finds all that the user has made while he/she was in his/her account
		//SELECT l.`ipAddress`,at.action_name ,tm.table_name ,r.result_name FROM `log` l, action_type at, table_modified tm, result r where l.id_actionType = at.id AND l.id_tableModified = tm.id AND l.id_result = r.id AND l.`id_user` = '.$_POST["id_user"].' order by l.`timeStamp`			
		$subject = $message. " from user ".$user_name;	
		$body_message= " <br> The login of the user ".$user_name." was ". $last_login . " and the logout was ".$logout_time_out."<br>";
		$body_message.= '<br> here is the activity report for the user '.$user_name .'<br>';
		$body_message.= '<table>
                                                <col width="170px">
                                                <col width="150px">
                                                <col width="150px">
                                                <col width="150px">
                                                <col width="170px">
												<col width="190px">
                                                <tr>
                                                        <th style="border: 1px solid;">Ip Address</th>
                                                        <th style="border: 1px solid;">Action Type</th>
                                                        <th style="border: 1px solid;">Table modified</th>
                                                        <th style="border: 1px solid;">Result</th>
                                                </tr>';
                                                

    $select_customers_query = 'SELECT l.`ipAddress` as ip ,at.action_name as ac_name ,tm.table_name as t_name ,r.result_name as r_name 
								FROM `log` l, action_type at, table_modified tm, result r 
								WHERE l.id_actionType = at.id AND l.id_tableModified = tm.id AND l.id_result = r.id AND l.`id_user` = '.$user.' order by l.`timeStamp` ';

    $select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');

    while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
		 $body_message.= "<tr>";
		 $body_message.= "<td style='border: 1px solid;'><span id='spanName'>" . $line['ip'] . "</span></td>";
		 $body_message.= "<td style='border: 1px solid;'><span id='spanName'>" . $line['ac_name'] . "</span></td>";
		 $body_message.= "<td style='border: 1px solid;'><span id='spanName'>" . $line['t_name'] . "</span></td>";
		 $body_message.= "<td style='border: 1px solid;'><span id='spanName'>" . $line['r_name'] . "</span></td>";
		 $body_message.= "</tr>";
    }
        $body_message.= '  </table>';
		
		// here ends the body of the event for email send
		//echo $body_message;
		$this-> send_email($subject,$body_message);
}

/*function body_email($message,$ip,$action_type,$result, $table_modified, $id_user, $logout_time_out){
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
		//$this-> enviar_correo($subject,$body_message);
}
*/



}
	
?>


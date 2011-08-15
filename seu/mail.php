<?php
 require_once "/usr/share/php/Mail.php";
 
 $from = "kolemp@o2.pl";
 $to = "przemyslaw.koltermann@wachowiakisyn.pl";
 $subject = "Hi!";
 $body = "Hi,\n\nHow are you?";
 
 $host = "poczta.o2.pl:587";
 $username = "kolemp";
 $password = "angelina";
 
 $headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'auth' => true,
     'username' => $username,
     'password' => $password));
 
 $mail = $smtp->send($to, $headers, $body);
 
 if (PEAR::isError($mail)) {
   echo("<p>" . $mail->getMessage() . "</p>");
  } else {
   echo("<p>Message successfully sent!</p>");
  }
 ?>

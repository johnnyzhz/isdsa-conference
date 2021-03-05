<?php

define ("EMAIL_ADDRESS", "xxx@gmail.com"); // email address to send messages
define ("EMAIL_USER", "xxx@gmail.com");
define ("EMAIL_PASS", "password");
define ("HOST", "ssl://smtp.gmail.com");
require_once("../lib/class.phpmailer.php");

function sendsmail($email, $subject, $message) {

  $mail = new PHPMailer();
  $mail->IsSMTP();
  $mail->SMTPDebug = 1;
  $mail->SMTPAuth  = true;
  $mail->Port    = 465;
  $mail->Host    = HOST;
  $mail->Username  = EMAIL_USER;
  $mail->Password  = EMAIL_PASS;
  $mail->AddReplyTo('meeting@isdsa.org','ISDSA Meeting');
  $mail->From    = "meeting@isdsa.org";
  $mail->FromName  = "ISDSA Meeting";
  $mail->WordWrap  = 80; // set word wrap
  $mail->AddAddress($email);
  $mail->Subject = $subject;
  $mail->MsgHTML($message);

  if (!$mail->Send()) {
    $error = "Email sent incorrectly";
  } else {
    $error = "Email sent correctly";
  }
}

#sendmail("email@", "This is a test", "This is a test");
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

$name = $_POST['name'];
$affiliation = $_POST['affiliation'];
$email = $_POST['email'];
$comment = $_POST['comment'];

$datafile = fopen("data.txt", "a") or die("Unable to open file!");
$userarray = array($name, $affiliation, $email, $comment);
$json = json_encode($userarray);
fwrite($datafile, $json);

## send email
$message = "Dear ".$name.",<br /><br />"."Thank you for your registration for the ISDSA meeting on June 5, 2021. Details regarding the meeting and workshop (if you indicated your interest) will be sent to your email on file two weeks prior to the meeting. If you have any questions, please do not hesitate to contact us.<br /><br />Thank you, <br /><br />2021 ISDSA Meeting Committee<br />meeting@isdsa.org<br />https://meeting.isdsa.org";
$subject = "Thank you for your ISDSA 2021 meeting registration";
			
include "email.php";
$mailoutput = sendmail($email, $subject, $message);
echo $mailoutput;
}
?>

<script type="text/javascript">
setTimeout(function(){window.location="https://meeting.isdsa.org";}, 3000)
</script>

<p>You will be directed back to the meeting website in 3 seconds. Or you can directly <a href="https://meeting.isdsa.org">click here to go back.</a></p>
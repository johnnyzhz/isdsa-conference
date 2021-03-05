<?php 
if ($_GET['st']=="Completed"){
	$pp_hostname = "www.paypal.com"; 
	$req = 'cmd=_notify-synch';
	$tx_token = $_GET['tx'];
	$auth_token = "get your paypal token and include it here"; 
	$req .= "&tx=$tx_token&at=$auth_token"; 
 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://$pp_hostname/cgi-bin/webscr");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	//set cacert.pem verisign certificate path in curl using 'CURLOPT_CAINFO' field here,
	//if your server does not bundled with default verisign  certificates.
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host:  $pp_hostname"));
	$res = curl_exec($ch);
	curl_close($ch);
	if(!$res){
		echo "Something went wrong. Please contact us at  meeting@isdsa.org with your PayPal payment information.";
	}else{
		// parse the data
		$lines = explode("\n", trim($res));
		$keyarray = array();
		if (strcmp ($lines[0], "SUCCESS") == 0) {
			for ($i = 1; $i < count($lines); $i++) {
				$temp = explode("=", $lines[$i],2);
				$keyarray[urldecode($temp[0])] = urldecode($temp[1]);
			}
    
			$name = $keyarray['option_selection2'];
			$email = $keyarray['option_selection3'];
			$payer_email = $keyarray['payer_email'];
			$payment = $keyarray['mc_gross'];
			$mc_fee = $keyarray['mc_fee'];
			
			echo ("<h2>Thank you for your registration and payment!</h2>");
			echo '<script type="text/javascript">setTimeout(function(){window.location="https://meeting.isdsa.org";}, 3000)</script><p>You will be directed back to the meeting website in 3 seconds. Or you can directly <a href="https://meeting.isdsa.org">click here to go back.</a></p>';
			
			$datafile = fopen("paypal.txt", "a") or die("Unable to open file!");
			$userarray = array($name, $email,$payer_email, $payment, $mc_fee, $comment);
			$json = json_encode($userarray);
			fwrite($datafile, $json);
			
			## send email
			$message = "Dear ".$name.",<br /><br />"."This is to acknowledge the receipt of your payment in the amount of $".$payment."  for the workshop on Statistical Power Analysis on June 4, 2021. Details regarding the workshop will be sent to your email on file two weeks prior to the workshop. If you have any questions, please do not hesitate to contact us.<br /><br />
			Note. Refund policy: <br />Before May 1, full registration fee minus 5% processing fee. <br />Before June 1, 80% of registration fee <br />After June 1, no refund.<br /><br />Thank you, <br /><br />2021 ISDSA Meeting Committee<br />meeting@isdsa.org<br />https://meeting.isdsa.org";
			$subject = "Thank you for your workshop registration";
			
			include "email.php";
			$mailoutput = sendmail($email, $subject, $message);
			//echo $mailoutput;
			
		}else {
			echo "Something went wrong. Please contact us at  meeting@isdsa.org with your PayPal payment information.";
		}
	}
}else{	
	echo "Something went wrong. Please contact us at meeting@isdsa.org with your PayPal payment information.";
} 
?>

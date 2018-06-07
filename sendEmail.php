<?php

    include('assets/siteInfo.php');

	header('Content-type: application/json');
	$status = array(
		'type'=>'success',
		'message'=>'Email sent!'
	);

    $name = @trim(stripslashes($_POST['name'])); 
    $email = @trim(stripslashes($_POST['email'])); 
    $subject = @trim(stripslashes($_POST['subject']));
    $message = @trim(stripslashes($_POST['message'])); 

    $email_template = 'emailTemplate.html';
    
    if (IS_DEV) {
        $to = 'tylerjaquish@gmail.com';
        $result = array( 'type' => 'success', 'message'=>'Unable to send email in dev.'  );
    } else {
        $to = 'joel@ohshootphotobooth.com';
    
        $headers  = "From: " . $name . ' <' . $email . '>' . "\r\n";
        $headers .= "Reply-To: ". $email . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $templateTags =  array(
            '{{subject}}' => $subject,
            '{{email}}'=>$email,
            '{{message}}'=>$message,
            '{{name}}'=>$name
            );

        $templateContents = file_get_contents( dirname(__FILE__) . '/'.$email_template);
        $contents =  strtr($templateContents, $templateTags);

        if (mail( $to, $subject, $contents, $headers)) {
            $result = array( 'type' => 'success', 'message'=>'<strong>Thank You!</strong>&nbsp; Your email has been delivered.' );
        } else {
            $result = array( 'type' => 'error', 'message'=>'<strong>Error!</strong>&nbsp; Cann\'t Send Mail.'  );
        }
    }

    echo json_encode($result);
    die;
?>
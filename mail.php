<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';


// passing true in constructor enables exceptions in PHPMailer
$mail = new PHPMailer(true);

try {
    // Server settings
    ///$mail->SMTPDebug = SMTP::DEBUG_SERVER; // for detailed debug output
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->Username = 'wasimofficial075@gmail.com'; // YOUR gmail email
    $mail->Password = 'gjep xchv ayhy ycqb'; // YOUR gmail password

    // Sender and recipient settings
    $mail->setFrom('wasimofficial075@gmail.com', 'Mohammed Wasim');
    $mail->addAddress('wasimofficial085@gmail.com', 'Wasim');
    $mail->addReplyTo('wasimofficial075@gmail.com', 'Mohammed Wasim' ); // to set the reply to

    // Setting the email content
    $mail->IsHTML(true);
    $mail->Subject = "Send email using Gmail SMTP and PHPMailer";
    $mail->Body = 'HTML message body. <b>Gmail</b> SMTP email body.';
    $mail->AltBody = 'Plain text message body for non-HTML email client. Gmail SMTP email body.';

    $mail->send();
    echo "Email message sent.";
} catch (Exception $e) {
    echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
}
?>
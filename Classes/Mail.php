<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require './vendor/PHPMailer/src/Exception.php';
require './vendor/PHPMailer/src/PHPMailer.php';
require './vendor/PHPMailer/src/SMTP.php';

Class Mail {
     private static function getMailer()
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();

        $mail->Host = "smtp.gmail.com";

        $mail->SMTPAuth = true;

        $mail->Username = "wasimofficial075@gmail.com";

        $mail->Password = "gjep xchv ayhy ycqb";

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;

        $mail->setFrom(
            "wasimofficial075@gmail.com",
            "Inventory Management System"
        );

        $mail->isHTML(true);

        return $mail;
    }

    public static function sendPasswordReset($email, $resetLink)
    {
        try {

            $mail = self::getMailer();

            $mail->addAddress($email);

            $mail->Subject = "Password Reset Request";

            $mail->Body = "
                <h2>Password Reset</h2>

                <p>Hello,</p>

                <p>
                    We received a request to reset your password.
                </p>

                <p>
                    Click the button below to reset it.
                </p>

                <p>
                    <a
                        href='{$resetLink}'
                        style='
                            background:#007bff;
                            color:#ffffff;
                            padding:12px 18px;
                            text-decoration:none;
                            border-radius:4px;
                        '>

                        Reset Password

                    </a>
                </p>

                <p>
                    This link expires in 30 minutes.
                </p>

                <p>
                    If you didn't request this,
                    you can safely ignore this email.
                </p>
            ";

            $mail->AltBody =
                "Reset your password:\n\n" .
                $resetLink .
                "\n\nThis link expires in 30 minutes.";

            return $mail->send();

        } catch (Exception $e) {

            error_log($mail->ErrorInfo);

            return false;
        }
    }



}
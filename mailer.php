<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();



    $mail->Host       = 'mail.gfi-edu.com';               // Your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'gfcilibrary@gfi-edu.com';        // Your email
    $mail->Password   = '0l)^v*8UI(8;';                    // Your email password (make sure this is secure!)
    $mail->SMTPSecure = 'ssl';      // Use SSL (for port 465)
    $mail->Port       = 465;                              // SSL port

    // Recipients
    $mail->setFrom('gfcilibrary@gfi-edu.com', 'GFCI Library');
    $mail->addAddress('kentjoshuazamoradaborbor@gmail.com', 'Recipient Name');  // Add your recipient

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email sent using PHPMailer and GFCI email server.';
    $mail->AltBody = 'This is a plain-text version of the message.';

    $mail->send();
    echo 'Message has been sent successfully.';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

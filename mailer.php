<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();



    $mail->Host       = 'smtp.hostinger.com';               // Your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'e-pcaxtca@pcaxtca.shop';        // Your email
    $mail->Password   = '=YIitlw3';                    // Your email password (make sure this is secure!)
    $mail->SMTPSecure = 'ssl';      // Use SSL (for port 465)
    $mail->Port       = 465;                              // SSL port

    // Recipients
    $mail->setFrom('e-pcaxtca@pcaxtca.shop', 'GFCI Library');  // ✅ Set this to match the Hostinger account
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

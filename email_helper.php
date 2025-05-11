<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";

function sendEmail($recipientEmail, $recipientName, $subject, $body) {
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
        $mail->setFrom('e-pcaxtca@pcaxtca.shop', 'GFCI Library');  // Set this to match the Hostinger account
        $mail->addAddress($recipientEmail, $recipientName);  // Dynamic recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);  // Plain text version of the message

        // Send the email
        $mail->send();
        return true;  // Return success
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";  // Return error message
    }
}
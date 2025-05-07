<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'mail.gfi-edu.com';
    $mail->Port       = 465;
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = 'ssl';

    // Email account credentials
    $mail->Username   = 'gfcilibrary@gfi-edu.com';
    $mail->Password   = '0l)^v*8UI(8;';

    // Sender and recipient
    $mail->setFrom('gfcilibrary@gfi-edu.com', 'GFCI Library');
    $mail->addAddress('kentjoshuazamoradaborbor@gmail.com', 'Recipient Name'); // Change to real recipient

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from GFCI Library';
    $mail->Body    = '<strong>Hello!</strong><br>This is a test email sent via PHPMailer.';
    $mail->AltBody = 'Hello! This is a test email sent via PHPMailer.';

    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
}

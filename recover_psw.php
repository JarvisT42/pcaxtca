<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery | GFI Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-indigo-600">AuthApp</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-gray-700 hover:text-indigo-600 font-medium underline">Login</a>
                    <a href="register.php" class="text-gray-700 hover:text-indigo-600 font-medium">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex items-center justify-center pt-12">
        <div class="w-full max-w-md bg-white rounded-xl shadow-2xl p-8 mx-4">
            <div class="space-y-6">
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-900">Password Recovery</h1>
                    <p class="mt-2 text-sm text-gray-600">Enter your email to reset your password</p>
                </div>

                <form action="#" method="POST" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            placeholder="Enter your email">
                    </div>

                    <button
                        type="submit"
                        name="recover"
                        class="w-full py-2.5 px-4 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Reset Password
                    </button>
                </form>

                <div class="text-center text-sm text-gray-600">
                    Remember your password?
                    <a href="login.php" class="text-blue-500 hover:text-blue-700 font-medium">Sign in here</a>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST["recover"])) {
    include('connect/connection.php');

    // Sanitize email input
    $email = mysqli_real_escape_string($connect, $_POST["email"]);

    $sql = mysqli_query($connect, "SELECT * FROM login WHERE email='$email'");

    if (mysqli_num_rows($sql) <= 0) {
        echo "<script>alert('Sorry, no account exists with this email.'); window.location.href='recover.php';</script>";
        exit();
    }

    $fetch = mysqli_fetch_assoc($sql);

    if ($fetch["status"] == 0) {
        echo "<script>alert('Your account must be verified before password recovery.'); window.location.href='index.php';</script>";
        exit();
    }

    // Include PHPMailer classes
    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';

    // Generate token
    $token = bin2hex(random_bytes(50));
    $_SESSION['token'] = $token;
    $_SESSION['email'] = $email;

    // Save token to database
    $createdAt = date("Y-m-d H:i:s");
    $tokenSql = "INSERT INTO password_reset_tokens (email, token, created_at) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connect, $tokenSql);
    mysqli_stmt_bind_param($stmt, "sss", $email, $token, $createdAt);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'mail.gfi-edu.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'gfcilibrary@gfi-edu.com';
        $mail->Password   = '0l)^v*8UI(8;';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('gfcilibrary@gfi-edu.com', 'GFCI Library');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "<b>Dear User</b>
            <h3>We received a password reset request</h3>
            <p>Click the link below to reset your password:</p>
            <a href='http://localhost:3000/login-system/reset_psw.php?token=$token'>Reset Password</a>
            <br><br>
            <p>If you didn't request this, please ignore this email.</p>
            <p>Best regards,<br>GFCI Library Team</p>";

        $mail->AltBody = "Password Reset Link: http://localhost:3000/login-system/reset_psw.php?token=$token";

        $mail->send();
        echo "<script>window.location.replace('notification.html');</script>";
        exit();
    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.location.href='recover.php';</script>";
        exit();
    }
}
?>
<?php session_start();
include('connect/connection.php');




if (!isset($_GET['token'])) {
    echo "<script>alert('Invalid or missing token.'); window.location.href='index.php';</script>";
    exit();
}

$token = $_GET['token'];

// Check if token exists and is valid
$sql = mysqli_query($connect, "SELECT * FROM password_reset_tokens WHERE token='$token'");
if (mysqli_num_rows($sql) === 0) {
    echo "<script>alert('Invalid or expired token.'); window.location.href='index.php';</script>";
    exit();
}

$tokenData = mysqli_fetch_assoc($sql);
$email = $tokenData['email'];
$_SESSION['email'] = $email;
$_SESSION['token'] = $token;


?>



<!------ Include the above in your HEAD tag ---------->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Password Recovery | GFI Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen font-[Inter]">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <span class="text-2xl font-bold text-indigo-600">AuthApp</span>
                <div class="space-x-4">
                    <a href="index.php" class="text-gray-700 hover:text-indigo-600 font-medium underline">Login</a>
                    <a href="register.php" class="text-gray-700 hover:text-indigo-600 font-medium">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Form -->
    <main class="flex items-center justify-center pt-12 px-4">
        <div class="w-full max-w-md bg-white rounded-xl shadow-2xl p-8">
            <div class="space-y-6">
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-900">Password Recovery</h1>
                    <p class="mt-2 text-sm text-gray-600">Enter your new password</p>
                </div>

                <form action="#" method="POST" name="login" class="space-y-5">

                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        <i class="fas fa-eye-slash absolute top-9 right-3 text-gray-400 cursor-pointer" id="togglePassword"></i>
                    </div>

                    <button type="submit" name="reset"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition">Reset Password</button>
                </form>

                <div class="text-center text-sm text-gray-600">
                    Remember your password?
                    <a href="login.php" class="text-blue-500 hover:text-blue-700 font-medium">Sign in here</a>
                </div>
            </div>
        </div>
    </main>

    <script>
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        toggle.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>

<?php
if (isset($_POST["reset"])) {
    $password = $_POST["password"];
    $email = $_SESSION['email'];
    $token = $_SESSION['token'];

    if (!$email || !$token) {
        echo "<script>alert('Session expired. Please request a new password reset.'); window.location.href='recover.php';</script>";
        exit();
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Update password
    $update = mysqli_query($connect, "UPDATE login SET password='$hash' WHERE email='$email'");

    if ($update) {
        // Delete token
        mysqli_query($connect, "DELETE FROM password_reset_tokens WHERE token='$token'");
        echo "<script>alert('Your password has been reset.'); window.location.href='index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Password reset failed. Try again.'); window.location.href='reset_psw.php?token=$token';</script>";
        exit();
    }
}
?>
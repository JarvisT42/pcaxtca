<?php session_start(); ?>
<?php
include('connect/connection.php');

if (isset($_POST["register"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $check_query = mysqli_query($connect, "SELECT * FROM login WHERE email = '$email'");
    $rowCount = mysqli_num_rows($check_query);

    if (!empty($email) && !empty($password)) {
        if ($rowCount > 0) {
?>
            <script>
                alert("User with this email already exists!");
            </script>
            <?php
        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            // Insert user with status=1 (assuming 1 means active)
            $result = mysqli_query($connect, "INSERT INTO login (email, password, status) VALUES ('$email', '$password_hash', 1)");

            if ($result) {
            ?>
                <script>
                    alert("Registration Successful!");
                    window.location.replace('index.php'); // Redirect to login page
                </script>
            <?php
            } else {
            ?>
                <script>
                    alert("Registration Failed");
                </script>
<?php
            }
        }
    }
}
?>


<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/@heroicons/v2/24/outline/index.min.js"></script>
    <style>
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .animated-gradient {
            background: linear-gradient(-45deg, #e0c3fc, #8ec5fc, #bae6ff, #b5fffc);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        .float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .form-entrance {
            animation: formEntrance 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes formEntrance {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="animated-gradient min-h-screen relative overflow-hidden">
    <!-- Floating background shapes -->
    <div class="absolute w-full h-full">
        <div class="float absolute left-20 top-1/4 w-48 h-48 bg-purple-200/30 rounded-full blur-xl"></div>
        <div class="float absolute right-32 top-1/3 w-64 h-64 bg-blue-200/30 rounded-full blur-xl animation-delay-2000"></div>
        <div class="float absolute left-1/3 bottom-1/4 w-32 h-32 bg-cyan-200/30 rounded-full blur-xl animation-delay-3000"></div>
    </div>

    <nav class="bg-white/80 backdrop-blur-md shadow-sm relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-indigo-600 hover:text-indigo-700 transition-colors">AuthApp</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">Login</a>
                    <a href="register.php" class="text-gray-700 hover:text-indigo-600 font-medium underline transition-colors">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex items-center justify-center pt-12 relative">
        <div class="form-entrance w-full max-w-md bg-white/90 backdrop-blur-lg rounded-xl shadow-2xl p-8 mx-4 transform transition-all hover:shadow-3xl hover:-translate-y-1">
            <div class="space-y-6">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-900">Welcome back</h1>
                    <p class="mt-2 text-gray-600">Sign in to your account</p>
                </div>

                <form method="POST" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <div class="mt-1">
                            <input
                                id="email"
                                name="email"
                                type="email"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                                placeholder="you@example.com">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1 relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none pr-12"
                                placeholder="••••••••">
                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute right-3 top-3.5 text-gray-400 hover:text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        name="register"
                        class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:opacity-90 transition-opacity">
                        Create Account
                    </button>

                    <div class="text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="index.php" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Sign in here
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function(e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('svg').classList.toggle('text-indigo-600');
        });
    </script>
</body>

</html>
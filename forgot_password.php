<?php

// Show errors (for development only — disable on production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";

$mail = new PHPMailer(true);

if (isset($_POST["recover"])) {
    include('connect/connection.php');

    // Sanitize email input
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($sql) <= 0) {
        echo "<script>alert('Sorry, no account exists with this email.'); window.location.href='recover.php';</script>";
        exit();
    }



    // Generate token
    $token = bin2hex(random_bytes(50));
    $_SESSION['token'] = $token;
    $_SESSION['email'] = $email;

    // Save token to database
    $createdAt = date("Y-m-d H:i:s");
    $tokenSql = "INSERT INTO password_reset_tokens (email, token, created_at) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $tokenSql);
    mysqli_stmt_bind_param($stmt, "sss", $email, $token, $createdAt);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

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
        $mail->setFrom('e-pcaxtca@pcaxtca.shop', 'Pcaxtca Shop');  // ✅ Set this to match the Hostinger account
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "<b>Dear User</b>
            <h3>We received a password reset request</h3>
            <p>Click the link below to reset your password:</p>
            <a href='https://pcaxtca.shop/reset_psw?token=$token'>Reset Password</a>
            <br><br>
            <p>If you didn't request this, please ignore this email.</p>
            <p>Best regards,<br>Pcaxtca Team</p>";

        $mail->AltBody = "Password Reset Link: https://pcaxtca.shop/login-system/reset_psw?token=$token";

        $mail->send();
        echo "<script>window.location.replace('notification.html');</script>";
        exit();
    } catch (Exception $e) {
        echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.location.href='recover.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <title>
        Soft UI Dashboard 3 by Creative Tim
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="">
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
                    <div class="container-fluid pe-0">
                        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="dashboard">
                            Soft UI Dashboard 3
                        </a>
                        <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon mt-2">
                                <span class="navbar-toggler-bar bar1"></span>
                                <span class="navbar-toggler-bar bar2"></span>
                                <span class="navbar-toggler-bar bar3"></span>
                            </span>
                        </button>
                        <div class="collapse navbar-collapse" id="navigation">
                            <ul class="navbar-nav mx-auto ms-xl-auto me-xl-7">
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="dashboard">
                                        <i class="fa fa-chart-pie opacity-6 text-dark me-1"></i>
                                        Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link me-2" href="profile">
                                        <i class="fa fa-user opacity-6 text-dark me-1"></i>
                                        Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link me-2" href="sign-up">
                                        <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                                        Sign Up
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link me-2" href="sign-in">
                                        <i class="fas fa-key opacity-6 text-dark me-1"></i>
                                        Sign In
                                    </a>
                                </li>
                            </ul>
                            <li class="nav-item d-flex align-items-center">
                                <a class="btn btn-round btn-sm mb-0 btn-outline-primary me-2" target="_blank" href="https://www.creative-tim.com/builder?ref=navbar-soft-ui-dashboard">Online Builder</a>
                            </li>
                            <ul class="navbar-nav d-lg-block d-none">
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/product/soft-ui-dashboard" class="btn btn-sm btn-round mb-0 me-1 bg-gradient-dark">Free download</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-75">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-8  border border-black p-4 rounded">
                                <div class="card-header pb-0 text-left bg-transparent">
                                    <h3 class="font-weight-bolder text-info text-gradient">Reset Password</h3>
                                    <p class="mb-0">Enter your email to reset send link</p>
                                </div>

                                <?php if (isset($_GET['send_1']) && $_GET['send_1'] == 1): ?>

                                    <div id="alert" class="alert alert-danger mx-4 text-center" role="alert" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">
                                        <strong>Error!</strong> Account Not Found.
                                    </div>
                                <?php endif; ?>

                                <div class="card-body">
                                    <form role="form" action="#" method="POST">
                                        <!-- CSRF Token (optional) -->

                                        <label for="emailInput" class="form-label">Email</label>
                                        <div class="mb-3">
                                            <input type="email" id="emailInput" name="email" class="form-control" placeholder="Enter your email address" required>
                                            <div id="emailFeedback" class="invalid-feedback mt-1"></div>
                                        </div>

                                        <div class="text-center">
                                            <button id="submitBtn" disabled type="submit" name="recover" class="btn bg-gradient-info w-100 mt-4 mb-0">
                                                Send Password Reset Link
                                            </button>
                                        </div>

                                        <div class="text-center mt-3">
                                            <a href="signin" class="text-info text-decoration-none" style="font-size: 0.9rem;">Back to Sign In</a>
                                        </div>
                                    </form>

                                    <script>
                                        const emailInput = document.getElementById('emailInput');
                                        const emailFeedback = document.getElementById('emailFeedback');
                                        const submitBtn = document.getElementById('submitBtn');

                                        emailInput.addEventListener('input', function(e) {
                                            const email = e.target.value;
                                            submitBtn.disabled = true; // Default to disabled

                                            if (!validateEmail(email)) {
                                                emailFeedback.textContent = 'Please enter a valid email address.';
                                                emailInput.classList.add('is-invalid');
                                                emailInput.classList.remove('is-valid');
                                                return;
                                            }

                                            checkEmailAvailability(email).then(data => {
                                                if (data.status === 'local') {
                                                    emailFeedback.textContent = '';
                                                    emailInput.classList.remove('is-invalid');
                                                    emailInput.classList.add('is-valid');
                                                    submitBtn.disabled = false;
                                                } else if (data.status === 'google_auth') {
                                                    emailFeedback.textContent = 'This email uses Google Sign-In. Please use Google authentication.';
                                                    emailInput.classList.add('is-invalid');
                                                    emailInput.classList.remove('is-valid');
                                                    submitBtn.disabled = true;
                                                } else if (data.status === 'not_registered') {
                                                    emailFeedback.textContent = 'This email is not registered.';
                                                    emailInput.classList.add('is-invalid');
                                                    emailInput.classList.remove('is-valid');
                                                    submitBtn.disabled = true;
                                                }
                                            }).catch(error => {
                                                console.error('Error:', error);
                                                emailFeedback.textContent = 'Error checking email availability.';
                                                submitBtn.disabled = true;
                                            });
                                        });



                                        function validateEmail(email) {
                                            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                            return re.test(String(email).toLowerCase());
                                        }

                                        function checkEmailAvailability(email) {
                                            return fetch('check_email_reset_psw.php', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/x-www-form-urlencoded',
                                                },
                                                body: 'email=' + encodeURIComponent(email)
                                            }).then(response => response.json());
                                        }
                                    </script>

                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        Don't have an account?
                                        <a href="sign-up" class="text-info text-gradient font-weight-bold">Sign up</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('assets/img/curved-images/curved6.jpg')"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-4 mx-auto text-center">
                    <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                        Company
                    </a>
                    <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                        About Us
                    </a>
                    <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                        Team
                    </a>
                    <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                        Products
                    </a>
                    <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                        Blog
                    </a>
                    <a href="javascript:;" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
                        Pricing
                    </a>
                </div>
                <div class="col-lg-8 mx-auto text-center mb-4 mt-2">
                    <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
                        <span class="text-lg fab fa-dribbble"></span>
                    </a>
                    <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
                        <span class="text-lg fab fa-twitter"></span>
                    </a>
                    <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
                        <span class="text-lg fab fa-instagram"></span>
                    </a>
                    <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
                        <span class="text-lg fab fa-pinterest"></span>
                    </a>
                    <a href="javascript:;" target="_blank" class="text-secondary me-xl-4 me-4">
                        <span class="text-lg fab fa-github"></span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-8 mx-auto text-center mt-1">
                    <p class="mb-0 text-secondary">
                        Copyright © <script>
                            document.write(new Date().getFullYear())
                        </script> Soft by Creative Tim.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <!--   Core JS Files   -->
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="assets/js/soft-ui-dashboard.min.js?v=1.1.0"></script>

    <script>
        // Set a timeout to hide the alert after 5 seconds (5000 ms)
        setTimeout(function() {
            var alertElement = document.getElementById('alert');
            if (alertElement) {
                alertElement.style.display = 'none';
            }
        }, 5000); // 5000 ms = 5 seconds
    </script>

</body>

</html>
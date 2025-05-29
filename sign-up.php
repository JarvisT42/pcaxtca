<!--
=========================================================
* Soft UI Dashboard 3 - v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<?php

// require __DIR__ . '/vendor/autoload.php';

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();

// $client = new Google\Client;
// $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
// $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
// $client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
// $client->addScope("email");
// $client->addScope("profile");

// $url = $client->createAuthUrl();



require __DIR__ . "/vendor/autoload.php";

// $client = new Google\Client;

// $client->setClientId("195730534849-kr4fp84dqijnsctm7dgc8eji9aq3hk6h.apps.googleusercontent.com");
// $client->setClientSecret("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");
// $client->setRedirectUri("http://pcaxtca.shop/redirect.php");
// $client->addScope("email");
// $client->addScope("profile");

// $url = $client->createAuthUrl();

$is_local = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

// Initialize Google Client
$client = new Google\Client();
$client->setClientId("195730534849-kr4fp84dqijnsctm7dgc8eji9aq3hk6h.apps.googleusercontent.com");

if ($is_local) {
  $client->setRedirectUri("http://localhost:3000/redirect.php");
} else {
  $client->setRedirectUri("http://pcaxtca.shop/redirect.php");
}


$client->setClientSecret("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");

$client->addScope("email");
$client->addScope("profile");

$client->setState('signup'); // or 'signin'
$signup_url = $client->createAuthUrl();


session_start();
require_once 'connect/connection.php'; // Include your database configuration

// Server-side form handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $first_name = $_POST['first_name'] ?? '';

  $last_name = $_POST['last_name'] ?? '';

  $password = $_POST['password'] ?? '';

  // ✅ Hash the password securely
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO users (email, first_name, last_name, password) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $email, $first_name, $last_name, $hashedPassword);

  if ($stmt->execute()) {
    $id = $stmt->insert_id; // Get inserted user's ID
    $_SESSION['user_id'] = $id;
    $_SESSION['email'] = $email;
    $_SESSION['user_logged_in'] = true;





    header("Location: shop");
    exit;
  } else {
    echo "Error inserting user: " . $stmt->error;
  }

  // This line won't be reached if insertion is successful
  header("Location: registration-success.php");
  exit;
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
  <!-- <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script> -->
  <!-- Add this in your <head> or before the closing </body> -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

</head>

<body class="">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent mt-4">
    <div class="container">
      <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-white" href="dashboard.php">
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
            <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="index.php">
              <i class="fa fa-chart-pie opacity-6  me-1"></i>
              Company
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="shop.php">
              <i class="fa fa-user opacity-6  me-1"></i>
              Shop
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="sign-up.php">
              <i class="fas fa-user-circle opacity-6  me-1"></i>
              Sign Up
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="sign-in.php">
              <i class="fas fa-key opacity-6  me-1"></i>
              Sign In
            </a>
          </li>
        </ul>
        <li class="nav-item d-flex align-items-center">
          <a class="btn btn-round btn-sm mb-0 btn-outline-white me-2" target="_blank" href="https://www.creative-tim.com/builder?ref=navbar-soft-ui-dashboard">Online Builder</a>
        </li>
        <ul class="navbar-nav d-lg-block d-none">
          <li class="nav-item">
            <a href="https://www.creative-tim.com/product/soft-ui-dashboard" class="btn btn-sm btn-round mb-0 me-1 bg-gradient-light">Free download</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->
  <main class="main-content  mt-0">
    <section class="min-vh-100 mb-8">
      <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('assets/img/curved-images/curved14.jpg');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5 text-center mx-auto">
              <h1 class="text-white mb-2 mt-5">Welcome!</h1>
              <p class="text-lead text-white">Use these awesome forms to login or create new account in your project for free.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10">
          <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
            <div class="card z-index-0">
              <div class="card-header text-center pt-4">
                <h5>Register with</h5>
              </div>




              <?php if (isset($_GET['registered_already']) && $_GET['registered_already'] == 1): ?>
                <div id="alert" class="alert alert-danger mx-4 text-center" role="alert" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">
                  <strong>Error!</strong> Email is already registered.
                </div>
              <?php endif; ?>



              <div class="row px-xl-5 px-sm-4 px-3 justify-content-center">
                <div class="col-12 px-1 d-flex justify-content-center">
                  <a class="btn d-flex align-items-center justify-content-center gap-2 w-100 py-2 shadow-sm"
                    href="<?= $signup_url ?>" style="max-width: 300px; border: 1px solid #000; color: #000;">
                    <svg width="24px" height="24px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                      <g fill="none" fill-rule="evenodd">
                        <g transform="translate(3,2)" fill-rule="nonzero">
                          <path d="M57.8123233,30.1515267 C57.8123233,27.7263183 57.6155321,25.9565533 57.1896408,24.1212666 L29.4960833,24.1212666 L29.4960833,35.0674653 L45.7515771,35.0674653 C45.4239683,37.7877475 43.6542033,41.8844383 39.7213169,44.6372555 L39.6661883,45.0037254 L48.4223791,51.7870338 L49.0290201,51.8475849 C54.6004021,46.7020943 57.8123233,39.1313952 57.8123233,30.1515267" id="Path" fill="#4285F4"></path>
                          <path d="M29.4960833,58.9921667 C37.4599129,58.9921667 44.1456164,56.3701671 49.0290201,51.8475849 L39.7213169,44.6372555 C37.2305867,46.3742596 33.887622,47.5868638 29.4960833,47.5868638 C21.6960582,47.5868638 15.0758763,42.4415991 12.7159637,35.3297782 L12.3700541,35.3591501 L3.26524241,42.4054492 L3.14617358,42.736447 C7.9965904,52.3717589 17.959737,58.9921667 29.4960833,58.9921667" id="Path" fill="#34A853"></path>
                          <path d="M12.7159637,35.3297782 C12.0932812,33.4944915 11.7329116,31.5279353 11.7329116,29.4960833 C11.7329116,27.4640054 12.0932812,25.4976752 12.6832029,23.6623884 L12.6667095,23.2715173 L3.44779955,16.1120237 L3.14617358,16.2554937 C1.14708246,20.2539019 0,24.7439491 0,29.4960833 C0,34.2482175 1.14708246,38.7380388 3.14617358,42.736447 L12.7159637,35.3297782" id="Path" fill="#FBBC05"></path>
                          <path d="M29.4960833,11.4050769 C35.0347044,11.4050769 38.7707997,13.7975244 40.9011602,15.7968415 L49.2255853,7.66898166 C44.1130815,2.91684746 37.4599129,0 29.4960833,0 C17.959737,0 7.9965904,6.62018183 3.14617358,16.2554937 L12.6832029,23.6623884 C15.0758763,16.5505675 21.6960582,11.4050769 29.4960833,11.4050769" id="Path" fill="#EB4335"></path>
                        </g>
                      </g>
                    </svg>
                    <span>Sign up with Google</span>
                  </a>
                </div>

                <div class="col-12 mt-3">
                  <div class="d-flex align-items-center justify-content-center position-relative">
                    <hr class="w-100" style="border-top: 1px solid #000;">
                    <span class="px-3 text-sm text-black bg-white position-absolute" style="z-index: 1;">or</span>
                  </div>
                </div>
              </div>








              <div class="card-body">



                <form role="form text-left" method="POST" action="" onsubmit="return validatePasswords()">
                  <div class="mb-3">
                    <input type="email" class="form-control" placeholder="Email" aria-label="Email"
                      id="emailInput" name="email" required
                      aria-describedby="email-addon">
                    <div id="emailFeedback" class="invalid-feedback"></div>
                  </div>

                  <div id="passwordSection" onsubmit="return validatePasswords()" style="display: none;">
                    <div class="mb-3">
                      <input type="text" class="form-control" placeholder="First Name"
                        aria-label="First Name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                      <input type="text" class="form-control" placeholder="Last Name"
                        aria-label="Last Name" name="last_name" required>
                    </div>

                    <div class="mb-3 position-relative">
                      <input type="password" class="form-control" placeholder="Password"
                        aria-label="Password" name="password" id="password" required minlength="8">
                      <i class="fa-solid fa-eye toggle-password" toggle="#password" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                      <div id="passwordError" class="text-danger small mt-1"></div>
                    </div>

                    <div class="mb-3 position-relative">
                      <input type="password" class="form-control" placeholder="Confirm Password"
                        aria-label="Confirm Password" name="confirm_password" id="confirm_password" required minlength="8">
                      <i class="fa-solid fa-eye toggle-password" toggle="#confirm_password" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                      <div id="confirmPasswordError" class="text-danger small mt-1"></div>
                    </div>


                    <div class="form-check form-check-info text-left">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                      <label class="form-check-label" for="flexCheckDefault">
                        I agree to the <a href="#" class="text-dark font-weight-bolder">Terms and Conditions</a>
                      </label>
                    </div>
                  </div>
                  <script>
                    function validatePasswords() {
                      const password = document.getElementById('password');
                      const confirmPassword = document.getElementById('confirm_password');
                      const passwordError = document.getElementById('passwordError');
                      const confirmPasswordError = document.getElementById('confirmPasswordError');

                      // Clear previous errors
                      passwordError.textContent = '';
                      confirmPasswordError.textContent = '';

                      let isValid = true;

                      if (password.value.length < 8) {
                        passwordError.textContent = 'Password must be at least 8 characters.';
                        isValid = false;
                      }

                      if (password.value !== confirmPassword.value) {
                        confirmPasswordError.textContent = 'Passwords do not match.';
                        isValid = false;
                      }

                      return isValid;
                    }

                    // Password show/hide toggle
                    document.querySelectorAll('.toggle-password').forEach(function(icon) {
                      icon.addEventListener('click', function() {
                        const input = document.querySelector(this.getAttribute('toggle'));
                        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                        input.setAttribute('type', type);
                        this.classList.toggle('fa-eye');
                        this.classList.toggle('fa-eye-slash');
                      });
                    });
                  </script>





                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2" id="submitBtn" disabled>
                      Sign up
                    </button>
                  </div>
                </form>
                <p class="text-sm mt-3 mb-0">Already have an account?
                  <a href="sign-in.php" class="text-dark font-weight-bolder">Sign in</a>
                </p>
                <script>
                  document.getElementById('emailInput').addEventListener('input', function(e) {
                    const email = e.target.value;
                    const emailFeedback = document.getElementById('emailFeedback');
                    const passwordSection = document.getElementById('passwordSection');
                    const submitBtn = document.getElementById('submitBtn');

                    if (validateEmail(email)) {
                      checkEmailAvailability(email).then(available => {
                        if (available) {
                          emailFeedback.textContent = '';
                          e.target.classList.remove('is-invalid');
                          passwordSection.style.display = 'block';
                          submitBtn.disabled = false;
                        } else {
                          emailFeedback.textContent = 'This email is already registered';
                          e.target.classList.add('is-invalid');
                          passwordSection.style.display = 'none';
                          submitBtn.disabled = true;
                        }
                      });
                    } else {
                      emailFeedback.textContent = 'Please enter a valid email address';
                      e.target.classList.add('is-invalid');
                      passwordSection.style.display = 'none';
                      submitBtn.disabled = true;
                    }
                  });

                  function validateEmail(email) {
                    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return re.test(String(email).toLowerCase());
                  }

                  function checkEmailAvailability(email) {
                    return fetch('check_email.php', {
                        method: 'POST',
                        headers: {
                          'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'email=' + encodeURIComponent(email)
                      })
                      .then(response => response.json())
                      .then(data => data.available)
                      .catch(error => {
                        console.error('Error:', error);
                        return false;
                      });
                  }
                </script>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
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
              Products
            </a>


          </div>

        </div>
        <div class="row">
          <div class="col-8 mx-auto text-center mt-1">
            <p class="mb-0 text-secondary">
              Copyright © <script>
                document.write(new Date().getFullYear())
              </script>
            </p>
          </div>
        </div>
      </div>
    </footer>
    <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  </main>
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
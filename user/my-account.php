<?php
include '../connect/connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    include '../connect/connection.php';

    // Validate user session
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $errors = [];

    // Validate and sanitize inputs
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone_number = trim($_POST['phone_number']);

    // Validate names
    if (empty($first_name) || !preg_match('/^[a-zA-Z\s\-]{2,50}$/', $first_name)) {
        $errors[] = "Invalid first name";
    }

    if (empty($last_name) || !preg_match('/^[a-zA-Z\s\-]{2,50}$/', $last_name)) {
        $errors[] = "Invalid last name";
    }

    // Validate phone number
    if (!preg_match('/^\+?[0-9]{7,15}$/', $phone_number)) {
        $errors[] = "Invalid phone number format";
    }

    // Handle file upload
    $profile_pic = null;
    if (!empty($_FILES['profile_picture']['name'])) {
        $allowed_types = ['image/jpeg', 'image/png'];
        $max_size = 1048576; // 1MB
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['profile_picture']['tmp_name']);

        if (!in_array($mime_type, $allowed_types)) {
            $errors[] = "Invalid file type. Only JPG/PNG allowed";
        }

        if ($_FILES['profile_picture']['size'] > $max_size) {
            $errors[] = "File too large. Max 1MB allowed";
        }

        if (empty($errors)) {
            $extension = str_replace('image/', '', $mime_type);
            $profile_pic = bin2hex(random_bytes(16)) . '.' . $extension;
            move_uploaded_file(
                $_FILES['profile_picture']['tmp_name'],
                'uploads/' . $profile_pic
            );
        }
    }

    if (empty($errors)) {
        try {
            // Update query
            $sql = "UPDATE users SET 
                    first_name = ?, 
                    last_name = ?, 
                    phone_number = ?, 
                    profile_pic = COALESCE(?, profile_pic)
                    WHERE id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "ssssi",
                $first_name,
                $last_name,
                $phone_number,
                $profile_pic,
                $user_id
            );

            if ($stmt->execute()) {
                $_SESSION['success'] = "Profile updated successfully";
            } else {
                $_SESSION['error'] = "Error updating profile: " . $stmt->error;
            }
            $stmt->close();
        } catch (Exception $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?added_success=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>

<body>
    <?php include 'header.php'; ?>

    <div class="breadcumb_area bg-img mt-custom" style="background-image: url(img/bg-img/breadcumb.jpg);">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="page-title text-center">
                        <h2>Account</h2>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .mt-custom {
                margin-top: 80px;
            }
        </style>
    </div>

    <section class="shop_grid_area pb-5">
        <div class="container">
            <div class="row">


                <?php include 'account-sidebar.php'; ?>



                <div class="col-md-9 mt-5">
                    <!-- Main Content Area -->
                    <?php
                    if (!isset($_SESSION['csrf_token'])) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    }
                    include '../connect/connection.php';
                    // Secure database query using prepared statements
                    $user_id = $_SESSION['user_id'];
                    $sql = "SELECT * FROM users WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();
                    ?>

                    <div class="account-content">
                        <h3>My Profile</h3>
                        <p class="text-muted">Manage and protect your account</p>

                        <form method="POST" action="" enctype="multipart/form-data">
                            <!-- CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">


                            <div class="form-group d-flex gap-3">
                                <div class="w-50">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" name="first_name"
                                        value="<?= htmlspecialchars($user['first_name']) ?>"
                                        pattern="[a-zA-Z\s\-]{2,50}"
                                        title="2-50 characters, letters and spaces only" required>
                                </div>
                                <div class="w-50">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" name="last_name"
                                        value="<?= htmlspecialchars($user['last_name']) ?>"
                                        pattern="[a-zA-Z\s\-]{2,50}"
                                        title="2-50 characters, letters and spaces only" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <div class="input-group">
                                    <input type="email" class="form-control"
                                        value="<?= htmlspecialchars($user['email']) ?>" readonly>
                                    <div class="input-group-append">
                                        <a href="change_email.php" class="btn btn-outline-secondary">Change</a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Phone Number</label>
                                <div class="input-group">
                                    <input type="tel" class="form-control" name="phone_number"
                                        value="<?= htmlspecialchars($user['phone_number']) ?>"
                                        pattern="\+?[0-9]{7,15}"
                                        title="Valid phone number (7-15 digits, + optional)" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button"
                                            data-toggle="modal" data-target="#verifyPhoneModal">Verify</button>
                                    </div>
                                </div>
                            </div>



                            <div class="form-group">
                                <label>Profile Picture</label>
                                <div class="custom-file">
                                    <input type="file" name="profile_picture" class="custom-file-input" id="customFile"
                                        accept=".jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="customFile">
                                        <?= $user['profile_pic'] ? 'Change current file' : 'Choose file' ?>
                                    </label>
                                </div>
                                <small class="form-text text-muted">File size: maximum 1 MB | File extension: .JPEG, .PNG</small>
                            </div>

                            <button type="submit" class="btn essence-btn btn-sm">Save Changes</button>
                        </form>
                    </div>
                </div>



            </div>
        </div>
    </section>

    <!-- Add Bootstrap JS and Popper.js -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> -->
    <style>
        .address-card {
            background: #fff;
            transition: all 0.3s ease;
        }

        .address-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .badge-success {
            background-color: #28a745;
            padding: 0.5em 0.8em;
        }

        @media (max-width: 768px) {
            .address-card {
                padding: 15px;
            }

            .btn-group {
                width: 100%;
                margin-top: 10px;
            }

            .btn-group .btn {
                flex: 1;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
            }
        }

        @media (max-width: 576px) {
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }

            .badge {
                font-size: 0.8rem;
            }
        }
    </style>
    <style>
        /* Add this to style the active tab */
        .list-group-item.active {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
            font-weight: 500;
        }

        .list-group-item-action:hover {
            background-color: #f8f9fa;
        }

        .tab-pane {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>

    <style>
        .account-sidebar {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .account-user {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .list-group-item {
            border: none;
            border-radius: 0;
            border-bottom: 1px solid #eee;
        }

        .account-content {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile-section {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-control {
            border-radius: 4px;
            margin-bottom: 1rem;
        }
    </style>

    <?php include 'footer.php'; ?>
</body>

</html>
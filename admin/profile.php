<?php
include 'auth_admin.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../connect/connection.php';

// Get current admin data
$admin_id = $_SESSION['admin_id'];
$query = "SELECT * FROM admin WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Initialize variables
$fullname = $admin['name'];
$email = $admin['email'];
$profile_picture = $admin['profile_picture'];
$success = '';
$error = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Update profile information
  if (isset($_POST['update_profile'])) {
    $new_fullname = $_POST['fullname'];
    $new_email = $_POST['email'];

    // Validate inputs
    if (empty($new_fullname) || empty($new_email)) {
      $error = "Full name and email cannot be empty!";
    } else {
      $update_query = "UPDATE admin SET name = ?, email = ? WHERE id = ?";
      $stmt = $conn->prepare($update_query);
      $stmt->bind_param("ssi", $new_fullname, $new_email, $admin_id);

      if ($stmt->execute()) {
        $fullname = $new_fullname;
        $email = $new_email;
        $success = "Profile updated successfully!";
      } else {
        $error = "Error updating profile: " . $conn->error;
      }
    }
  }

  // Update password
  if (isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    if (!password_verify($current_password, $admin['password'])) {
      $error = "Current password is incorrect!";
    } elseif ($new_password !== $confirm_password) {
      $error = "New passwords do not match!";
    } elseif (strlen($new_password) < 8) {
      $error = "Password must be at least 8 characters!";
    } else {
      $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
      $update_query = "UPDATE admin SET password = ? WHERE id = ?";
      $stmt = $conn->prepare($update_query);
      $stmt->bind_param("si", $hashed_password, $admin_id);

      if ($stmt->execute()) {
        $success = "Password updated successfully!";
      } else {
        $error = "Error updating password: " . $conn->error;
      }
    }
  }

  // Update profile picture (BLOB storage)
  if (isset($_POST['update_avatar']) && isset($_FILES['profile_picture'])) {
    // Check if file is uploaded
    if ($_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
      // Get image details
      $tmp_name = $_FILES['profile_picture']['tmp_name'];
      $image_info = getimagesize($tmp_name);

      // Check if valid image
      if ($image_info === false) {
        $error = "File is not a valid image.";
      } elseif ($_FILES['profile_picture']['size'] > 1000000) { // 1MB limit
        $error = "Image size exceeds 1MB limit.";
      } else {
        // Read image data
        $img_data = file_get_contents($tmp_name);

        // Get MIME type
        $mime_type = $image_info['mime'];

        // Update database with image BLOB and MIME type
        $update_query = "UPDATE admin SET profile_picture = ?, profile_mime = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $img_data, $mime_type, $admin_id);

        if ($stmt->execute()) {
          $success = "Profile picture updated successfully!";
          // Refresh profile data to show updated image
          $stmt = $conn->prepare($query);
          $stmt->bind_param("i", $admin_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $admin = $result->fetch_assoc();
          $profile_picture = $admin['profile_picture'];
        } else {
          $error = "Error updating profile picture: " . $conn->error;
        }
      }
    } else {
      $error = "Error uploading file: " . $_FILES['profile_picture']['error'];
    }
  }
}
?>

<?php include 'admin_header.php'; ?>

<body class="g-sidenav-show  bg-gray-100">
  <?php include 'sidebar.php'; ?>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>
    <!-- End Navbar -->

    <div class="container-fluid py-4">
      <!-- Success/Error Messages -->
      <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <span class="alert-icon"><i class="ni ni-like-2"></i></span>
          <span class="alert-text"><?= $success ?></span>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <span class="alert-icon"><i class="ni ni-notification-83"></i></span>
          <span class="alert-text"><?= $error ?></span>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Profile Settings</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="container">
                <div class="row">
                  <!-- Left Column - Profile Picture -->
                  <div class="col-md-4">
                    <div class="card shadow-sm mt-4">
                      <div class="card-body text-center">
                        <div class="avatar-upload">
                          <form method="post" enctype="multipart/form-data">
                            <div class="avatar-edit">
                              <input type="file" id="profile_picture" name="profile_picture" accept=".png, .jpg, .jpeg" class="d-none" />
                              <label for="profile_picture" class="btn btn-sm btn-primary">
                                <i class="fas fa-camera me-1"></i> Change Photo
                              </label>
                            </div>
                            <div class="avatar-preview mt-3">
                              <?php if (!empty($admin['profile_picture'])): ?>
                                <img id="imagePreview" src="data:<?= $admin['profile_mime'] ?>;base64,<?= base64_encode($admin['profile_picture']) ?>"
                                  class="rounded-circle" width="150" height="150" alt="Profile Picture">
                              <?php else: ?>
                                <div id="imagePreview" class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                  style="width:150px;height:150px;margin:0 auto;">
                                  <i class="fas fa-user fa-3x text-secondary"></i>
                                </div>
                              <?php endif; ?>
                            </div>
                            <button type="submit" name="update_avatar" class="btn btn-sm btn-primary mt-3">
                              <i class="fas fa-sync me-1"></i> Update Picture
                            </button>
                          </form>
                        </div>
                        <h5 class="mt-3 mb-1"><?= htmlspecialchars($fullname) ?></h5>
                        <p class="text-muted mb-0"><?= htmlspecialchars($email) ?></p>
                        <p class="text-muted mb-0">Administrator</p>
                      </div>
                    </div>
                  </div>

                  <!-- Right Column - Profile Forms -->
                  <div class="col-md-8">
                    <!-- Profile Information Form -->
                    <div class="card shadow-sm mt-4">
                      <div class="card-header bg-light">
                        <h5 class="mb-0">Profile Information</h5>
                      </div>
                      <div class="card-body">
                        <form method="post">
                          <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="fullname" value="<?= htmlspecialchars($fullname) ?>" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>" required>
                          </div>
                          <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Changes
                          </button>
                        </form>
                      </div>
                    </div>

                    <!-- Password Change Form -->
                    <div class="card shadow-sm mt-4">
                      <div class="card-header bg-light">
                        <h5 class="mb-0">Change Password</h5>
                      </div>
                      <div class="card-body">
                        <form method="post">
                          <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="current_password" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" required>
                            <div class="form-text">Must be at least 8 characters</div>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                          </div>
                          <button type="submit" name="update_password" class="btn btn-primary">
                            <i class="fas fa-key me-1"></i> Update Password
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script>
    // Profile picture preview
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          // Create image element if it doesn't exist
          if (!$('#imagePreview').is('img')) {
            $('#imagePreview').replaceWith('<img id="imagePreview" class="rounded-circle" width="150" height="150" src="' + e.target.result + '" alt="Preview">');
          } else {
            $('#imagePreview').attr('src', e.target.result);
          }
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#profile_picture").change(function() {
      readURL(this);
    });

    // Auto-hide alerts after 5 seconds
    $(document).ready(function() {
      setTimeout(function() {
        $('.alert').fadeOut('slow');
      }, 5000);
    });
  </script>
</body>

<?php include 'admin_footer.php'; ?>
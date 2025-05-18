<?php
include '../connect/connection.php';
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
                    <div class="account-content">
                        <h3>My Profile</h3>
                        <p class="text-muted">Manage and protect your account</p>

                        <form>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" value="kentjoshuazamoradaborbor" readonly>
                            </div>

                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" placeholder="Enter your name">
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <div class="input-group">
                                    <input type="email" class="form-control" value="ke*************@gmail.com" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button">Change</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Phone Number</label>
                                <div class="input-group">
                                    <input type="tel" class="form-control" value="**********47" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button">Change</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Gender</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="male">
                                        <label class="form-check-label" for="male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                                        <label class="form-check-label" for="female">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="other" value="other">
                                        <label class="form-check-label" for="other">Other</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Profile Picture</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFile" accept=".jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                                <small class="form-text text-muted">File size: maximum 1 MB | File extension: .JPEG, .PNG</small>
                            </div>
                            <button type="submit" class="btn essence-btn btn-sm">Save Changes</button>


                        </form>
                        <!-- Add bank & card content here -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
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

            <div class="row ">
                <div class="col-md-3 mt-5 ">
                    <!-- Sidebar Navigation -->
                    <div class="account-sidebar ">
                        <div class="account-user">
                            <h4>kentjoshuazamoradaborbor</h4>
                            <p>Edit Profile</p>
                        </div>
                        <nav class="list-group" id="sidebarTabs" role="tablist">
                            <a class="list-group-item list-group-item-action active" data-toggle="tab" href="#profile" role="tab">Profile</a>
                            <a class="list-group-item list-group-item-action" data-toggle="tab" href="#banks" role="tab">Banks & Cards</a>
                            <a class="list-group-item list-group-item-action" data-toggle="tab" href="#addresses" role="tab">Addresses</a>
                            <a class="list-group-item list-group-item-action" data-toggle="tab" href="#password" role="tab">Change Password</a>
                            <a class="list-group-item list-group-item-action" data-toggle="tab" href="#privacy" role="tab">Privacy Settings</a>
                            <a class="list-group-item list-group-item-action" data-toggle="tab" href="#notifications" role="tab">Notification Settings</a>
                            <a class="list-group-item list-group-item-action" data-toggle="tab" href="#purchases" role="tab">My Purchase</a>
                            <a class="list-group-item list-group-item-action" data-toggle="tab" href="#vouchers" role="tab">My Vouchers</a>
                            <a class="list-group-item list-group-item-action" data-toggle="tab" href="#coins" role="tab">My Shopee Coins</a>
                        </nav>
                    </div>
                </div>

                <div class="col-md-9 mt-5">
                    <!-- Main Content Area -->
                    <div class="tab-content " id="nav-tabContent">
                        <!-- Profile Content -->
                        <div class="tab-pane fade account-content " id="purchases" role="tabpanel">

                            <h3>purchase</h3>
                            <p class="text-muted">purchase</p>

                            <ul class="nav nav-tabs purchase-tabs mb-4" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#all">All</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#to-pay">To Pay</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#to-ship">To Ship</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#to-receive">To Receive</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#completed">Completed</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#cancelled">Cancelled</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#return-refund">Return/Refund</a>
                                </li>
                            </ul>



                            <div class="tab-content">
                                <!-- All Orders -->
                                <div class="tab-pane fade show active" id="all" role="tabpanel">
                                    <!-- Order 1 -->
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <!-- Order Header -->
                                            <div class="d-flex justify-content-between mb-3">
                                                <div>
                                                    <h5>Hall of Brand</h5>
                                                    <small class="text-muted">Parcel has been delivered • Completed</small>
                                                </div>
                                                <div>
                                                    <button class="btn btn-outline-secondary btn-sm">Chat</button>
                                                    <button class="btn btn-outline-secondary btn-sm">View Shop</button>
                                                </div>
                                            </div>

                                            <!-- Order Items -->
                                            <div class="row border-top pt-3">
                                                <div class="col-2">
                                                    <img src="product-image.jpg" class="img-fluid" alt="Product">
                                                </div>
                                                <div class="col-6">
                                                    <h6>24 Hours Mechanical Electrical Plug Program Timer</h6>
                                                    <small class="text-muted">x1 • Pre-Order</small>
                                                    <p class="mt-2">
                                                        <span class="text-muted text-decoration-line-through">₱499</span>
                                                        <span class="text-danger">₱162</span>
                                                    </p>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <h6>Order Total: ₱207</h6>
                                                    <div class="mt-3">
                                                        <button class="btn btn-outline-primary btn-sm">Rate</button>
                                                        <button class="btn btn-outline-secondary btn-sm">Contact Seller</button>
                                                        <button class="btn btn-outline-success btn-sm">Buy Again</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Repeat similar structure for other orders -->
                                </div>

                                <!-- Other Status Tabs (similar structure) -->
                                <div class="tab-pane fade" id="to-pay" role="tabpanel">
                                    <h6>to payyyy7</h6>
                                </div>
                                <div class="tab-pane fade" id="to-ship" role="tabpanel">
                                    <!-- To Ship content -->
                                </div>
                                <!-- Add remaining status tabs -->
                            </div>


                            <!-- Add bank & card content here -->

                        </div>
                        <style>
                            .purchase-tabs .nav-link {
                                font-size: 0.9rem;
                                padding: 0.5rem 1rem;
                                color: #666;
                            }

                            .purchase-tabs .nav-link.active {
                                color: #ee4d2d;
                                border-bottom: 2px solid #ee4d2d;
                            }

                            .order-card {
                                border: 1px solid #eee;
                                border-radius: 4px;
                                margin-bottom: 1rem;
                            }

                            .order-status {
                                color: #ee4d2d;
                                font-weight: 500;
                            }
                        </style>

                        <div class="tab-pane fade show active account-content" id="profile" role="tabpanel">
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

                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>

                        <!-- Banks & Cards Content -->


                        <!-- Addresses Content -->
                        <div class="tab-pane fade account-content" id="addresses" role="tabpanel">
                            <h3>My Profile</h3>
                            <p class="text-muted">Manage your shipping addresses</p>



                            <div class="mb-4">
                                <button class="btn btn-primary btn-sm">+ Add New Address</button>
                            </div>

                            <!-- Address Card -->
                            <div class="address-card mb-4 p-4 border rounded">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="mb-1">Kent Joshua Daborbor</h5>
                                        <p class="mb-1 text-muted">(+63) 916 629 8647</p>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-outline-secondary btn-sm">Edit</button>
                                        <button class="btn btn-outline-danger btn-sm">Delete</button>
                                    </div>
                                </div>

                                <p class="mb-3">
                                    PH-2 B5,L8 10 SALANGSANG<br>
                                    San Isidro (Lagao 2Nd), General Santos City<br>
                                    South Cotabato, Mindanao, 9500
                                </p>

                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge badge-success">Default</span>
                                    <button class="btn btn-outline-secondary btn-sm">Set as default</button>
                                    <button class="btn btn-outline-secondary btn-sm">Pickup Address</button>
                                    <button class="btn btn-outline-secondary btn-sm">Return Address</button>
                                </div>


                                <!-- Add more address cards as needed -->
                            </div>
                        </div>


                        <div class="tab-pane fade account-content " id="banks" role="tabpanel">
                            <div class="profile-section">
                                <h3>Banks & Cards</h3>
                                <p class="text-muted">Manage your payment methods</p>
                                <!-- Add bank & card content here -->
                            </div>
                        </div>
                        <!-- Password Content -->
                        <div class="tab-pane fade account-content" id="password" role="tabpanel">
                            <div class="profile-section">
                                <h3>Change Password</h3>
                                <p class="text-muted">Update your security settings</p>
                                <!-- Add password change form here -->
                            </div>
                        </div>

                        <!-- Add more tab panes for other sections -->
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
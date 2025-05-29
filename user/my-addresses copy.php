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
                    <div class="account-content p-4 bg-white shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0">Addresses</h3>
                            <button class="essence-btn btn-sm">Add New Address</button>
                        </div>
                        <p class="text-muted mb-4">Manage your delivery addresses</p>

                        <!-- Address Cards -->
                        <div class="row">
                            <!-- Sample Address Card -->
                            <div class="col-md-6 mb-4">
                                <div class="address-card p-4 border">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="mb-0">Home Address</h5>
                                        <div class="btn-group">
                                            <button class="btn btn-link text-primary p-0">Edit</button>
                                            <button class="btn btn-link text-danger p-0 ml-2">Delete</button>
                                        </div>
                                    </div>
                                    <p class="mb-1">John Doe<br>
                                        123 Main Street<br>
                                        Apt 4B<br>
                                        New York, NY 10001<br>
                                        United States</p>
                                    <p class="mb-0 text-muted">Phone: +1 234 567 890</p>
                                </div>
                            </div>

                            <!-- Add New Address Card -->
                            <div class="col-md-6 mb-4">
                                <div class="address-card p-4 border text-center d-flex align-items-center justify-content-center"
                                    style="height: 100%; min-height: 200px; cursor: pointer;">
                                    <div>
                                        <i class="fa fa-plus-circle fa-2x text-muted mb-2"></i>
                                        <h5 class="text-muted">Add New Address</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>

</html>

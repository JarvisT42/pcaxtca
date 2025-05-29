<?php
include '../connect/connection.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $addressId = intval($_POST['address_id']);

    // Example: Delete from database
    $stmt = $conn->prepare("DELETE FROM user_address WHERE id = ?");
    $stmt->bind_param("i", $addressId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<div class='alert alert-success'>Address deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Failed to delete address.</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add':
            // Validate and sanitize inputs
            $address = [
                'user_id' => $user_id,
                'address_name' => htmlspecialchars($_POST['address_name']),
                'address' => htmlspecialchars($_POST['address']),
                'postcode' => htmlspecialchars($_POST['postcode']),
                'city' => htmlspecialchars($_POST['city']),
                'state' => htmlspecialchars($_POST['state']),
                'province' => htmlspecialchars($_POST['province']),
                'phone' => htmlspecialchars($_POST['phone'])
            ];

            $stmt = $conn->prepare("INSERT INTO user_address 
                (user_id, address_name, address, postcode, city, state, province, phone) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "isssssss",
                $address['user_id'],
                $address['address_name'],
                $address['address'],
                $address['postcode'],
                $address['city'],
                $address['state'],
                $address['province'],
                $address['phone']
            );

            if ($stmt->execute()) {
                $_SESSION['message'] = "Address added successfully";
            }
            break;

            // Add cases for edit and delete
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?added_success=1");
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
                    <?php
                    include '../connect/connection.php';


                    // Fetch all addresses for the current user
                    $user_id = $_SESSION['user_id'];
                    $sql = "SELECT * FROM user_address WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    ?>

                    <!-- Main Content Area -->
                    <div class="account-content p-4 bg-white shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0">Addresses</h3>
                            <button class="essence-btn btn-sm" data-toggle="modal" data-target="#addAddressModal">
                                Add New Address
                            </button>
                        </div>
                        <p class="text-muted mb-4">Manage your delivery addresses</p>

                        <!-- Address Cards -->
                        <div class="row">
                            <?php if ($result->num_rows > 0) : ?>
                                <?php while ($address = $result->fetch_assoc()) : ?>
                                    <div class="col-md-6 mb-4">
                                        <div class="address-card p-4 border">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="mb-0"><?= htmlspecialchars($address['address_name'] ?? 'Address') ?></h5>
                                                <div class="btn-group">
                                                    <button class="btn btn-link text-primary p-0 edit-address"
                                                        data-id="<?= $address['id'] ?>"
                                                        data-address_name="<?= htmlspecialchars($address['address_name']) ?>"
                                                        data-address="<?= htmlspecialchars($address['address']) ?>"
                                                        data-postcode="<?= htmlspecialchars($address['postcode']) ?>"
                                                        data-city="<?= htmlspecialchars($address['city']) ?>"
                                                        data-state="<?= htmlspecialchars($address['state']) ?>"
                                                        data-province="<?= htmlspecialchars($address['province']) ?>"
                                                        data-phone="<?= htmlspecialchars($address['phone']) ?>">Edit</button>
                                                    <form method="POST" action="" enctype="multipart/form-data">
                                                        <input type="hidden" name="address_id" value="<?= $address['id'] ?>">
                                                        <button type="submit" name="delete" class="btn btn-link text-danger p-0 ml-2 delete-address">
                                                            Delete
                                                        </button>
                                                    </form>


                                                </div>
                                            </div>
                                            <p class="mb-1">
                                                <?= htmlspecialchars($address['address']) ?><br>
                                                <?= htmlspecialchars($address['postcode']) ?><br>
                                                <?= htmlspecialchars($address['city']) ?><br>
                                                <?= htmlspecialchars($address['state']) ?>,
                                                <?= htmlspecialchars($address['province']) ?><br>
                                                Phone: <?= htmlspecialchars($address['phone']) ?>
                                            </p>
                                            <small class="text-muted">
                                                Added: <?= date('M d, Y', strtotime($address['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endwhile; ?>

                                <!-- Edit Address Modal (outside the loop!) -->
                                <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form id="editAddressForm" method="POST" action="">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editAddressModalLabel">Edit Address</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" id="editAddressId" />

                                                    <div class="mb-3">
                                                        <label for="editAddressName" class="form-label">Address Name</label>
                                                        <input type="text" class="form-control" id="editAddressName" name="address_name" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="editAddress" class="form-label">Street Address</label>
                                                        <textarea class="form-control" id="editAddress" name="address" rows="3" required></textarea>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="editPostcode" class="form-label">Postcode</label>
                                                            <input type="text" class="form-control" id="editPostcode" name="postcode" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="editCity" class="form-label">City</label>
                                                            <input type="text" class="form-control" id="editCity" name="city" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="editState" class="form-label">State</label>
                                                            <input type="text" class="form-control" id="editState" name="state" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="editProvince" class="form-label">Province</label>
                                                            <input type="text" class="form-control" id="editProvince" name="province" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="editPhone" class="form-label">Phone</label>
                                                        <input type="tel" class="form-control" id="editPhone" name="phone" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            <?php else : ?>
                                <div class="col-12">
                                    <div class="alert alert-info">No addresses found. Add your first address!</div>
                                </div>
                            <?php endif; ?>


                            <!-- Add New Address Card -->
                            <div class="col-md-6 mb-4">
                                <div class="address-card p-4 border text-center d-flex align-items-center justify-content-center"
                                    style="height: 100%; min-height: 200px; cursor: pointer;"
                                    data-toggle="modal" data-target="#addAddressModal">
                                    <div>
                                        <i class="fa fa-plus-circle fa-2x text-muted mb-2"></i>
                                        <h5 class="text-muted">Add New Address</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Address Modal -->
                <div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Address</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="">
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="add">
                                    <div class="form-group">
                                        <label>Address Name (e.g., Home, Office)</label>
                                        <input type="text" name="address_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Street Address</label>
                                        <textarea name="address" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Postcode</label>
                                            <input type="text" name="postcode" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>City</label>
                                            <input type="text" name="city" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>State</label>
                                            <input type="text" name="state" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Province</label>
                                            <input type="text" name="province" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="tel" name="phone" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn essence-btn">Save Address</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editButtons = document.querySelectorAll('.edit-address');
            const modal = new bootstrap.Modal(document.getElementById('editAddressModal'));

            editButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Get data from data attributes
                    document.getElementById('editAddressId').value = button.getAttribute('data-id');
                    document.getElementById('editAddressName').value = button.getAttribute('data-address_name');
                    document.getElementById('editAddress').value = button.getAttribute('data-address');
                    document.getElementById('editPostcode').value = button.getAttribute('data-postcode');
                    document.getElementById('editCity').value = button.getAttribute('data-city');
                    document.getElementById('editState').value = button.getAttribute('data-state');
                    document.getElementById('editProvince').value = button.getAttribute('data-province');
                    document.getElementById('editPhone').value = button.getAttribute('data-phone');

                    modal.show();
                });
            });
        });
    </script>

    <?php include 'footer.php'; ?>
</body>

</html>
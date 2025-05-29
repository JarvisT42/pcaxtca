<?php
include 'auth_admin.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../connect/connection.php';

if (isset($_GET['ban'])) {
    $userId = intval($_GET['ban']);

    // Ban the user (e.g., set status to 'banned')
    $banQuery = "UPDATE users SET status = 'banned' WHERE id = ?";
    $stmt = $conn->prepare($banQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Optional: Redirect to avoid repeated banning on refresh
    header("Location: " . $_SERVER['PHP_SELF'] . "?banned_success=1");
    exit;
}

?>


<?php include 'admin_header.php'; ?>



<body class="g-sidenav-show  bg-gray-100">
    <?php include 'sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <?php include 'navbar.php'; ?>

        <!-- End Navbar -->

        <!-- Button trigger modal -->


        <!-- Modal -->



        <div class="container-fluid py-4 ">
            <div class="row">
                <div class="col-12">
                    <?php if (isset($_GET['banned_success']) && $_GET['banned_success'] == 1): ?>
                        <div class="alert alert-danger alert-dismissible fade show auto-dismiss" role="alert">

                            <div>User was successfully banned.</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <script src="timeoutMessage.js"></script>






                    <div class="card mb-4">

                        <div class="card-body p-4">












                            <table id="myTable" class="display">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Email Verified</th>
                                        <th>Joined Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT 
                                id, 
                                CONCAT(first_name, ' ', last_name) AS full_name,
                                email,
                                phone_number,
                                email_verify_at,
                                created_at,
                                status 
                                FROM users
                                ORDER BY created_at DESC";

                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    $count = 1;
                                    while ($user = $result->fetch_assoc()):

                                    ?>
                                        <tr>
                                            <td><?= $count++ ?></td>
                                            <td><?= htmlspecialchars($user['id']) ?></td>
                                     
                                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td><?= htmlspecialchars($user['phone_number']) ?></td>
                                            <td>
                                                <?= $user['email_verify_at'] ? date('M d, Y', strtotime($user['email_verify_at'])) : 'Not Verified' ?>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                            <td>
                                                <?= htmlspecialchars($user['status']) ?>
                                            </td>
                                            <td>
                                                <a href="?ban=<?= $user['id'] ?>"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to ban this user?');">
                                                    Banned?
                                                </a>
                                            </td>



                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>



                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!--   Core JS Files   -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Load DataTables JS (via CDN) -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable(); // Initialize DataTable on the table
        });
    </script>
</body>

<?php include 'admin_footer.php'; ?>
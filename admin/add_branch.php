<?php
include 'auth_admin.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../connect/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $store_branch = htmlspecialchars($_POST['store_branch']);
    $store_location = htmlspecialchars($_POST['store_location']);

    $stmt = $conn->prepare("INSERT INTO pm_pickup_store (store_branch, store_location) VALUES (?,?)");
    if ($stmt) {
        $stmt->bind_param("ss", $store_branch, $store_location);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: " . $_SERVER['PHP_SELF'] . "?added_success=1");
            exit();
        } else {
            $error = "Failed to insert branch.";
        }
        $stmt->close();
    } else {
        $error = "Failed to prepare statement.";
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM pm_pickup_store WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?delete_success=1");
    } else {
        echo "Error deleting record.";
    }

    $stmt->close();
    $conn->close();
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
            <div class="row">
                <div class="col-12">

                    <?php if (isset($_GET['added_success']) && $_GET['added_success'] == 1): ?>
                        <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert">
                            Product branch added successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['delete_success']) && $_GET['delete_success'] == 1): ?>
                        <div class="alert alert-danger alert-dismissible fade show auto-dismiss" role="alert">

                            <div>Pickup store was successfully deleted.</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>




                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <script src="timeoutMessage.js"></script>



                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Add New Product Branch</h6>
                        </div>
                        <div class="card-body px-4 pt-0 pb-2">
                            <!--  -->

                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="store_branch" class="form-label">Branch</label>
                                    <input type="text" name="store_branch" id="store_branch" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="store_location" class="form-label">Location</label>
                                    <input type="text" name="store_location" id="store_location" class="form-control" required>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary">
                                    Add Branch
                                </button>
                            </form>

                        </div>
                    </div>


                    <div class="card mb-4">

                        <div class="card-body p-4">


                            form

                            <table id="myTable" class="display">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>branch</th>
                                        <th>date created</th>
                                        <th>location</th>

                                        <th>action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // 1. Create SQL query
                                    $sql = "SELECT * FROM pm_pickup_store";

                                    // 2. Execute the query
                                    $result = $conn->query($sql);

                                    // 3. Check and loop through results
                                    if ($result && $result->num_rows > 0) {
                                        $i = 1; // Initialize row number

                                        while ($row = $result->fetch_assoc()) {
                                            echo "<td>" . $i++ . "</td>"; // Show row number instead of ID
                                            echo "<td>" . htmlspecialchars($row["store_branch"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["store_location"]) . "</td>";

                                            echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                                            echo "<td>
                        <form method='POST' action='' onsubmit='return confirm(\"Are you sure you want to delete this entry?\")'>
                            <input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>
                            <button type='submit' name='delete' class='btn btn-danger btn-sm'>Delete</button>
                        </form>
                      </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr>
                <td colspan='3'>No pickup stores found.</td>
                <td style='display:none'></td> <!-- Hidden columns to match count -->
                <td style='display:none'></td>
            </tr>";
                                    }

                                    // 4. Close the connection
                                    $conn->close();
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Load DataTables JS (via CDN) -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable(); // Initialize DataTable on the table
        });
    </script>
    <!--   Core JS Files   -->

</body>

<?php include 'admin_footer.php'; ?>
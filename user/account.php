<?php
// Get active tab from URL
$activeTab = $_GET['tab'] ?? 'profile';
?>

<section class="shop_grid_area pb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 mt-5">
                <?php include 'account-sidebar.php'; ?>
            </div>

            <div class="col-md-9 mt-5">
                <?php
                // Load appropriate content based on active tab
                switch ($activeTab) {
                    case 'profile':
                        include 'profile-content.php';
                        break;
                    case 'purchases':
                        include 'purchases-content.php';
                        break;
                    case 'addresses':
                        include 'addresses-content.php';
                        break;
                    // Add cases for other tabs
                    default:
                        include 'profile-content.php';
                }
                ?>
            </div>
        </div>
    </div>
</section>
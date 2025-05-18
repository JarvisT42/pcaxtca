<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="col-md-3 mt-5">
    <!-- Sidebar Navigation -->
    <div class="account-sidebar">
        <div class="account-user">
            <h4>kentjoshuazamoradaborbor</h4>
            <p>Edit Profile</p>
        </div>
        <nav class="list-group">
            <a class="<?php echo ($current_page == 'my-account.php') ? 'active' : ''; ?>" href="my-account.php">Profile</a>
            <a class="<?php echo ($current_page == 'my-bank-card.php') ? 'active' : ''; ?>" href="my-bank-card.php">Banks & Cards</a>
            <a href="my-addresses.php" class="<?php echo ($current_page == 'my-addresses.php') ? 'active' : ''; ?>">Address</a>
            <a class="<?php echo ($current_page == 'my-purchase.php') ? 'active' : ''; ?>" href="my-purchase.php">
                My Purchase</a>

        </nav>
    </div>
</div>

<style>
    /* Enhanced Sidebar Styling */
    .account-sidebar {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }

    .account-user {
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
        margin-bottom: 20px;
    }

    .account-user h4 {
        font-size: 1.2rem;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .account-user p {
        color: #666;
        font-size: 0.9rem;
    }
</style>
<style>
    /* Base style for all navigation links */
    .list-group a {
        display: inline-block;
        min-width: 180px;
        height: 50px;
        line-height: 50px;
        padding: 0 40px;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1.5px;
        font-weight: 600;
        color: #0315ff;
        background-color: transparent;
        border: 2px solid #0315ff;
        text-decoration: none;
        margin-bottom: 5px;
        transition: all 0.3s ease;
    }

    /* Active state (same as essence-btn) */
    .list-group a.active {
        color: #ffffff !important;
        background-color: #0315ff;
        border-color: transparent;
    }

    /* Hover effect for non-active links */
    .list-group a:not(.active):hover {
        background-color: #0315ff;
        color: #ffffff !important;
    }

    /* Hover effect for active links */
    .list-group a.active:hover {
        background-color: #dc0345;
    }

    /* Existing essence-btn styles (for other buttons) */
    .essence-btn {
        /* Your existing button styles */
        display: inline-block;
        min-width: 180px;
        height: 50px;
        color: #ffffff;
        border: none;
        border-radius: 0;
        padding: 0 40px;
        text-transform: uppercase;
        font-size: 12px;
        line-height: 50px;
        background-color: #0315ff;
        letter-spacing: 1.5px;
        font-weight: 600;
    }

    .essence-btn:hover,
    .essence-btn:focus {
        color: #ffffff;
        background-color: #dc0345;
    }
</style>
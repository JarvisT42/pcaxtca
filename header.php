 <header class="header_area">
     <div class="classy-nav-container breakpoint-off d-flex align-items-center justify-content-between">
         <!-- Classy Menu -->
         <nav class="classy-navbar" id="essenceNav">
             <!-- Logo -->
             <a class="nav-brand" href="index">Pcaxtca Shop</a>
             <!-- Navbar Toggler -->
             <div class="classy-navbar-toggler">
                 <span class="navbarToggler"><span></span><span></span><span></span></span>
             </div>
             <!-- Menu -->
             <div class="classy-menu">
                 <!-- close btn -->
                 <div class="classycloseIcon">
                     <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                 </div>
                 <!-- Nav Start -->
                 <div class="classynav">
                     <ul>
                         <li><a href="shop">Shop</a>

                         </li>

                         <li><a href="blog">Blog</a></li>
                         <li><a href="contact">Contact</a></li>
                     </ul>
                 </div>
                 <!-- Nav End -->
             </div>
         </nav>

         <!-- Header Meta Data -->
         <div class="header-meta d-flex clearfix justify-content-end">
             <!-- Search Area -->
             <div class="search-area">
                 <form action="#" method="post">
                     <input type="search" name="search" id="headerSearch" placeholder="Type for search">
                     <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                 </form>
             </div>
             <!-- Favourite Area -->

             <!-- User Login Info -->
             <div class="user-login-info dropdown">
                 <?php if ($is_logged_in): ?>
                     <a href="#" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <img src="img/core-img/user.svg" alt="">
                     </a>
                     <div class="dropdown-menu dropdown-menu-right custom-dropdown" aria-labelledby="userDropdown">
                         <a class="dropdown-item" href="my-account.php">My Account</a>
                         <a class="dropdown-item" href="my-purchase.php">My Purchase</a>
                         <div class="dropdown-divider"></div>
                         <a class="dropdown-item" href="sign-out.php">Logout</a>
                     </div>
                 <?php else: ?>
                     <a href="sign-in.php">
                         <img src="img/core-img/user.svg" alt="">
                     </a>
                 <?php endif; ?>
             </div>
             <style>
                 .dropdown-menu {
                     padding: 0;
                     /* Remove default padding from dropdown menu */
                     border-radius: 4px;
                     /* Optional: maintain rounded corners */
                 }

                 .dropdown-item {
                     padding: 0.75rem 1.5rem;
                     /* Ensure proper padding */
                     display: block;
                     /* Make links block elements */
                     transition: all 0.2s ease;
                     /* Smooth transition */
                 }

                 .dropdown-menu .dropdown-item:hover,
                 .dropdown-menu .dropdown-item:focus,
                 .dropdown-menu .dropdown-item:active {
                     background-color: blue;
                     color: white !important;
                     /* Better contrast for text */
                     margin: 0;
                     /* Remove any potential margins */
                     width: 100%;
                     /* Ensure full width */
                 }
             </style>
             <!-- Cart Area -->
             <div class="cart-area">
                 <a href="#" id="essenceCartBtn">
                     <img src="img/core-img/bag.svg" alt="Cart">
                     <span><?= count($cartItems) ?></span>
                 </a>
             </div>
         </div>

     </div>
 </header>
  <?php include 'head.php';
    ?>



  <body>
      <!-- ##### Header Area Start ##### -->
      <?php include 'header.php'; ?>

      <?php if (isset($_GET['registered_already'])): ?>
          <div id="signup-message" style="position: fixed; top: 80px; right: 20px; background-color: #ffc107; padding: 15px; border-radius: 5px; z-index: 9999;">
              <strong>Notice:</strong> You are already signed up!
          </div>
          <script>
              setTimeout(function() {
                  const msg = document.getElementById('signup-message');
                  if (msg) {
                      msg.style.display = 'none';
                  }
              }, 5000); // 10 seconds = 10000 milliseconds
          </script>
      <?php endif; ?>


      <!-- ##### Welcome Area Start ##### -->
      <section class="welcome_area bg-img background-overlay" style="background-image: url(img/bg-img/bgnew.png);">
          <div class="container h-100">
              <div class="row h-100 align-items-center">
                  <!-- <div class="col-12">
                      <div class="hero-content">
                          <h6>Ravi</h6>
                          <h2>Winter Collection</h2>
                          <a href="#" class="btn essence-btn">view collection</a>
                      </div>
                  </div> -->
              </div>
          </div>
      </section>
      <!-- ##### Welcome Area End ##### -->

      <!-- ##### Top Catagory Area Start ##### -->
      <div class="top_catagory_area section-padding-80 clearfix">
          <div class="container">
              <div class="row justify-content-center">
                  <!-- Single Category -->
                  <div class="col-12 col-sm-6 col-md-4">
                      <div class="single_catagory_area d-flex align-items-center justify-content-center bg-img" style="background-image: url('img/bg-img/phone1.jpg'); height: 300px;">
                          <div class="catagory-content">
                              <a href="#">Phone</a>
                          </div>
                      </div>
                  </div>
                  <!-- Single Category -->
                  <div class="col-12 col-sm-6 col-md-4">
                      <div class="single_catagory_area d-flex align-items-center justify-content-center bg-img" style="background-image: url('img/bg-img/p3.jpg'); height: 300px;">
                          <div class="catagory-content">
                              <a href="#">Tempered Glass</a>
                          </div>
                      </div>
                  </div>
                  <!-- Single Category -->
                  <div class="col-12 col-sm-6 col-md-4">
                      <div class="single_catagory_area d-flex align-items-center justify-content-center bg-img" style="background-image: url('img/bg-img/p2.png'); height: 300px;">
                          <div class="catagory-content">
                              <a href="#">Accessories</a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>

      </div>
      <!-- ##### Top Catagory Area End ##### -->

      <!-- ##### CTA Area Start ##### -->
      <div class="cta-area">
          <div class="container">
              <div class="row">
                  <div class="col-12">
                      <div class="cta-content bg-img background-overlay" style="background-image: url(img/bg-img/bgnew2.png);">
                          <div class="h-100 d-flex align-items-center justify-content-end">
                              <div class="cta--text">
                                  <h6>-60%</h6>
                                  <h2>Global Sale</h2>
                                  <a href="#" class="btn essence-btn">Buy Now</a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- ##### CTA Area End ##### -->

      <!-- ##### New Arrivals Area Start ##### -->
      <section class="new_arrivals_area section-padding-80 clearfix">
          <div class="container">
              <div class="row">
                  <div class="col-12">
                      <div class="section-heading text-center">
                          <h2>Popular Products</h2>
                      </div>
                  </div>
              </div>
          </div>

          <div class="container">
              <div class="row">
                  <div class="col-12">
                      <div class="popular-products-slides owl-carousel">

                          <!-- Single Product -->
                          <div class="single-product-wrapper">
                              <!-- Product Image -->
                              <div class="product-img">
                                  <img src="img/product-img/p1 (1).jpg" alt="">
                                  <!-- Hover Thumb -->
                                  <img class="hover-img" src="img/product-img/p1 (1).jpg" alt="">
                                  <!-- Favourite -->
                                  <div class="product-favourite">
                                      <a href="#" class="favme fa fa-heart"></a>
                                  </div>
                              </div>
                              <!-- Product Description -->
                              <div class="product-description">
                                  <span>topshop</span>
                                  <a href="single-product-details.html">
                                      <h6>Knot Front Mini Dress</h6>
                                  </a>
                                  <p class="product-price">$80.00</p>

                                  <!-- Hover Content -->
                                  <div class="hover-content">
                                      <!-- Shop -->
                                      <div class="add-to-cart-btn">
                                          <a href="#" class="btn essence-btn">Shop</a>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <!-- Single Product -->
                          <div class="single-product-wrapper">
                              <!-- Product Image -->
                              <div class="product-img">
                                  <img src="img/product-img/p1 (2).jpg" alt="">
                                  <!-- Hover Thumb -->
                                  <img class="hover-img" src="img/product-img/p1 (2).jpg" alt="">
                                  <!-- Favourite -->
                                  <div class="product-favourite">
                                      <a href="#" class="favme fa fa-heart"></a>
                                  </div>
                              </div>
                              <!-- Product Description -->
                              <div class="product-description">
                                  <span>topshop</span>
                                  <a href="single-product-details.html">
                                      <h6>Poplin Displaced Wrap Dress</h6>
                                  </a>
                                  <p class="product-price">$80.00</p>

                                  <!-- Hover Content -->
                                  <div class="hover-content">
                                      <!-- Shop -->
                                      <div class="add-to-cart-btn">
                                          <a href="#" class="btn essence-btn">Shop</a>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <!-- Single Product -->
                          <div class="single-product-wrapper">
                              <!-- Product Image -->
                              <div class="product-img">
                                  <img src="img/product-img/p1 (3).jpg" alt="">
                                  <!-- Hover Thumb -->
                                  <img class="hover-img" src="img/product-img/p1 (3).jpg" alt="">

                                  <!-- Product Badge -->
                                  <div class="product-badge offer-badge">
                                      <span>-30%</span>
                                  </div>

                                  <!-- Favourite -->
                                  <div class="product-favourite">
                                      <a href="#" class="favme fa fa-heart"></a>
                                  </div>
                              </div>
                              <!-- Product Description -->
                              <div class="product-description">
                                  <span>mango</span>
                                  <a href="single-product-details.html">
                                      <h6>PETITE Crepe Wrap Mini Dress</h6>
                                  </a>
                                  <p class="product-price"><span class="old-price">$75.00</span> $55.00</p>

                                  <!-- Hover Content -->
                                  <div class="hover-content">
                                      <!-- Shop -->
                                      <div class="add-to-cart-btn">
                                          <a href="#" class="btn essence-btn">Shop</a>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <!-- Single Product -->
                          <div class="single-product-wrapper">
                              <!-- Product Image -->
                              <div class="product-img">
                                  <img src="img/product-img/p1 (4).jpg" alt="">
                                  <!-- Hover Thumb -->
                                  <img class="hover-img" src="img/product-img/p1 (4).jpg" alt="">

                                  <!-- Product Badge -->
                                  <div class="product-badge new-badge">
                                      <span>New</span>
                                  </div>

                                  <!-- Favourite -->
                                  <div class="product-favourite">
                                      <a href="#" class="favme fa fa-heart"></a>
                                  </div>
                              </div>
                              <!-- Product Description -->
                              <div class="product-description">
                                  <span>mango</span>
                                  <a href="single-product-details.html">
                                      <h6>PETITE Belted Jumper Dress</h6>
                                  </a>
                                  <p class="product-price">$80.00</p>

                                  <!-- Hover Content -->
                                  <div class="hover-content">
                                      <!-- Shop -->
                                      <div class="add-to-cart-btn">
                                          <a href="#" class="btn essence-btn">Shop</a>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>
      <!-- ##### New Arrivals Area End ##### -->
      <!-- Store Name -->
      <!-- Store Name -->
      <div class="fw-bold fs-4 mb-3 text-center text-primary">
          <h1>PAGE Cellphone Accessories</h1>
      </div>

      <!-- Branches -->
      <div class="brands-area d-flex justify-content-between gap-3 flex-wrap px-4 py-5 bg-light rounded shadow-sm text-center">
          <!-- Single Branch -->
          <div class="single-brands-logo bg-white rounded shadow-sm px-3 py-2">
              <h5 class="text-muted fst-italic mb-0">📍 Branch: Alabel</h5>
          </div>
          <div class="single-brands-logo bg-white rounded shadow-sm px-3 py-2">
              <h5 class="text-muted fst-italic mb-0">📍 Branch: Alabel</h5>
          </div>
          <div class="single-brands-logo bg-white rounded shadow-sm px-3 py-2">
              <h5 class="text-muted fst-italic mb-0">📍 Branch: Koronadal</h5>
          </div>
          <div class="single-brands-logo bg-white rounded shadow-sm px-3 py-2">
              <h5 class="text-muted fst-italic mb-0">📍 Branch: General Santos</h5>
          </div>
      </div>



      <!-- ##### Brands Area Start ##### -->

      <!-- ##### Brands Area End ##### -->

      <!-- ##### Footer Area Start ##### -->
      <footer class="footer_area clearfix">
          <div class="container">
              <div class="row">
                  <!-- Single Widget Area -->
                  <div class="col-12 col-md-6">
                      <div class="single_widget_area d-flex mb-30">
                          <!-- Logo -->
                          <div class="footer-logo mr-50">
                              <a href="#"><img src="img/core-img/logo2.png" alt=""></a>
                          </div>
                          <!-- Footer Menu -->
                          <div class="footer_menu">
                              <ul>
                                  <li><a href="shop.html">Shop</a></li>
                                  <li><a href="blog.html">Blog</a></li>
                                  <li><a href="contact.html">Contact</a></li>
                              </ul>
                          </div>
                      </div>
                  </div>
                  <!-- Single Widget Area -->
                  <div class="col-12 col-md-6">
                      <div class="single_widget_area mb-30">
                          <ul class="footer_widget_menu">
                              <li><a href="#">Order Status</a></li>
                              <li><a href="#">Payment Options</a></li>
                              <li><a href="#">Shipping and Delivery</a></li>
                              <li><a href="#">Guides</a></li>
                              <li><a href="#">Privacy Policy</a></li>
                              <li><a href="#">Terms of Use</a></li>
                          </ul>
                      </div>
                  </div>
              </div>

              <div class="row align-items-end">
                  <!-- Single Widget Area -->
                  <div class="col-12 col-md-6">
                      <div class="single_widget_area">
                          <div class="footer_heading mb-30">
                              <h6>Subscribe</h6>
                          </div>
                          <div class="subscribtion_form">
                              <form action="#" method="post">
                                  <input type="email" name="mail" class="mail" placeholder="Your email here">
                                  <button type="submit" class="submit"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
                              </form>
                          </div>
                      </div>
                  </div>
                  <!-- Single Widget Area -->
                  <div class="col-12 col-md-6">
                      <div class="single_widget_area">
                          <div class="footer_social_area">
                              <a href="#" data-toggle="tooltip" data-placement="top" title="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                              <a href="#" data-toggle="tooltip" data-placement="top" title="Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                              <a href="#" data-toggle="tooltip" data-placement="top" title="Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                              <a href="#" data-toggle="tooltip" data-placement="top" title="Pinterest"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                              <a href="#" data-toggle="tooltip" data-placement="top" title="Youtube"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="row mt-5">
                  <div class="col-md-12 text-center">
                      <p>
                          <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                          Copyright &copy;<script>
                              document.write(new Date().getFullYear());
                          </script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                          <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                      </p>
                  </div>
              </div>

          </div>
      </footer>
      <!-- ##### Footer Area End ##### -->

      <!-- jQuery (Necessary for All JavaScript Plugins) -->
      <script src="js/jquery/jquery-2.2.4.min.js"></script>
      <!-- Popper js -->
      <script src="js/popper.min.js"></script>
      <!-- Bootstrap js -->
      <script src="js/bootstrap.min.js"></script>
      <!-- Plugins js -->
      <script src="js/plugins.js"></script>
      <!-- Classy Nav js -->
      <script src="js/classy-nav.min.js"></script>
      <!-- Active js -->
      <script src="js/active.js"></script>

  </body>

  </html>
<?php
  include 'partials/header.php';

  include 'controller/login.php';
?> 

<section class="login d-flex">
  <div class="login-left w-50 h-100">
    <div class="row justify-content-center align-items-center h-100">
      <div class="col-6">
        <div class="header">
          <h1>Welcome Back</h1>
          <p>Welcome back! Please enter your details</p>
        </div>
        
        <?php
        if (isset($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        ?>

        <div class="login-form">
          <!-- Form Login -->
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required> 

              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required> 

              <button type="submit" class="login mt-3">Login</button> 
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="login-right w-50 h-100">   
    <div id="carouselLoginFade" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
      <div class="carousel-inner">
        <!-- <div class="carousel-item active">
          <img src="img/login/1.svg" class="d-block w-100" alt="Slide 1">
        </div> -->
        <div class="carousel-item active">
          <img src="img/login/2.svg" class="d-block w-100" alt="Slide 1">
        </div>
        <div class="carousel-item">
          <img src="img/login/3.svg" class="d-block w-100" alt="Slide 2">
        </div>
      </div>
    </div>
  </div>
</section>

<?php
    session_start();
    include '../../controller/koneksi.php';

?>

<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      nama="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Dashboard - Kantor</title>

    <style>
      .fixed-size-img {
          width: 100%;
          height: 200px; 
          object-fit: cover;
      }

    .btn-small {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    </style>


    <meta nama="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        <?php 
          include '../../partials/aside.php';
        ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <?php 
            include '../../partials/navbar.php';
          ?>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
              <!-- Content -->
            <?php
              $query = "SELECT u.role, p.nama, b.* FROM user u
              JOIN profil p ON u.id_user = p.id_user 
              JOIN barang b ON b.id_user = u.id_user
              WHERE role = 'Distributor'";
              $result = $koneksi->query($query);

              $namaDistributor = '';

              if ($result->num_rows > 0) {
                  $row = $result->fetch_assoc();
                  $namaDistributor = $row["nama"];
              } else {
                  $namaDistributor = "Unknown";
              }
            ?>
              <div class="container-xxl flex-grow-1 container-p-y">
                  <div class="row mb-3">
                      <div class="col">
                          <nav aria-label="breadcrumb">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item"><a href="order_k.php">Order</a></li>
                                  <li class="breadcrumb-item active" aria-current="page">Detail Produk - <?php echo $namaDistributor; ?></li>
                              </ol>
                          </nav>
                      </div>
                      <div class="col text-end">
                        <button type="submit" class="btn btn-primary"  onclick="validateAndSubmit()">Order Barang</button>
                      </div>
                  </div>

                  <?php

                  if (isset($_SESSION['pesan'])) {
                      echo "<div class='alert alert-success' id='notification'>{$_SESSION['pesan']}</div>";

                      echo "<script>
                          setTimeout(function() {
                          document.getElementById('notification').remove();
                          }, 3000);
                      </script>";

                      unset($_SESSION['pesan']);
                  }
                  ?>

                  <form id="orderForm" method="post" action="../../controller/tambah_order.php">
                      <div class="row">
                          <?php
                          // Reset pointer result set ke baris pertama
                          if ($result->num_rows > 0) {
                              $result->data_seek(0);
                              // Output data dari setiap row
                              while ($row = $result->fetch_assoc()) {
                                  ?>
                                  <input type="hidden" name="id_barang[]" value="<?php echo $row["id_barang"] ?>">
                                  <input type="hidden" name="harga[]" value="<?php echo $row["harga"] ?>">
                                  <input type="hidden" name="id_distributor" value="<?php echo $row["id_user"] ?>">
                                  <div class="col-md-3 mb-3">
                                      <div class="card shadow p-2">
                                          <img src="../../img/upload/barang/<?php echo $row["gambar_barang"] ?>" class="card-img-top fixed-size-img" alt="<?php echo $row["gambar_barang"] ?>">
                                          <div class="card-body p-0">
                                              <div class="accordion" id="accordionExample-<?php echo $row["id_barang"] ?>">
                                                  <div class="accordion-item">
                                                      <h2 class="accordion-header" id="heading-<?php echo $row["id_barang"] ?>">
                                                          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $row["id_barang"] ?>" aria-expanded="true" aria-controls="collapse-<?php echo $row["id_barang"] ?>">
                                                              <strong><?php echo $row["nama_barang"] ?></strong>
                                                          </button>
                                                      </h2>
                                                      <div id="collapse-<?php echo $row["id_barang"] ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $row["id_barang"] ?>" data-bs-parent="#accordionExample-<?php echo $row["id_barang"] ?>">
                                                          <div class="accordion-body">
                                                              <strong>Kategori:</strong> <?php echo $row["kategori"] ?><br>
                                                              <strong>Stok:</strong><?php echo $row["stok"] ?><br>
                                                              <strong>Harga:</strong> <?php echo $row["harga"] ?><br>
                                                             
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="d-flex justify-content-between align-items-center p-3">
                                                      <button class="btn btn-outline-secondary btn-sm" type="button" onclick="decreaseValue('quantity-<?php echo $row["id_barang"] ?>')">-</button>
                                                      <input type="number" name="quantities[]" id="quantity-<?php echo $row["id_barang"] ?>" class="form-control text-center" value="0" min="0" style="width: 100px;">
                                                      <button class="btn btn-outline-secondary btn-sm" type="button" onclick="increaseValue('quantity-<?php echo $row["id_barang"] ?>')">+</button>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                          <?php
                              }
                          } else {
                              echo "0 results";
                          }

                          $koneksi->close();
                          ?>
                      </div>
                      
                  </form>
              </div>

              <!-- / Content -->

              <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <script>
      function increaseValue(id) {
          var value = parseInt(document.getElementById(id).value, 10);
          value = isNaN(value) ? 0 : value;
          value++;
          document.getElementById(id).value = value;
      }

      function decreaseValue(id) {
        var value = parseInt(document.getElementById(id).value, 10);
        value = isNaN(value) ? 0 : value;
        value = value < 1 ? 1 : value;
        value--;
        document.getElementById(id).value = value;
      }

      function validateAndSubmit() {
          var inputs = document.querySelectorAll('input[name="quantities[]"]');
          var hasValidInput = false;

          inputs.forEach(function(input) {
              if (parseInt(input.value, 10) > 0) {
                  hasValidInput = true;
              }
          });

          if (hasValidInput) {
              document.getElementById('orderForm').submit();
          } else {
              alert('Masukkan setidaknya satu item dengan jumlah lebih dari 0');
          }
      }
    </script>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../../assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

  </body>
</html>
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
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Dashboard - Kantor</title>

    <style>
      /* CSS untuk mengubah kursor */
      .clickable-row {
        cursor: pointer; /* Mengubah kursor menjadi tanda panah */
      }
    </style>

    <meta name="description" content="" />

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
            <div class="container-xxl flex-grow-1 container-p-y">
              <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="order_k.php">Order</a></li>
                      <li class="breadcrumb-item active" aria-current="page">My Order</li>
                  </ol>
                </nav>

              <div class="card">
                <div class="table-responsive text-nowrap">
                  <table class="table table-striped table-hover" id="pemesananTable">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>tgl pemesanan</th>
                        <th>Distributor</th>
                        <th>Total Barang</th>
                        <th>total harga</th>
                        <th>status</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">   
                      <?php
                        $query = " SELECT p.*, SUM(pd.jumlah) AS total_jumlah, pr.nama
                        FROM pemesanan p
                        JOIN pemesanan_dtl pd ON p.id_pemesanan = pd.id_pemesanan
                        JOIN barang b ON pd.id_barang = b.id_barang
                        JOIN user u ON u.id_user = p.id_user2
                        JOIN profil pr ON pr.id_user = u.id_user
                        WHERE p.id_user = '{$_SESSION['id']}' AND status = 'Menunggu' 
                        GROUP BY p.id_pemesanan DESC";
                        $result = $koneksi->query($query);

                        if ($result && $result->num_rows > 0) {
                          $no = 1;
                          while ($row = $result->fetch_assoc()) {
                            echo "<tr class='clickable-row' data-href='riwayat_order_k_dtl.php?id={$row['id_pemesanan']}'>
                                    <td>" . $no . "</td>
                                    <td>" . $row['tgl_pemesanan'] . "</td>
                                    <td>" . $row['nama'] . "</td>
                                    <td>" . $row['total_jumlah'] . "</td>
                                    <td>" . $row['total_harga'] . "</td>
                                    <td>" . $row['status'] . "</td>
                                </tr>";
                            $no++;
                          }
                        } else {
                          echo "<tr><td colspan='5'>Tidak ada data peminjaman barang</td></tr>";
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>

              <script>
                document.addEventListener("DOMContentLoaded", function() {
                  const rows = document.querySelectorAll("#pemesananTable .clickable-row");
                  rows.forEach(row => {
                    row.addEventListener("click", function() {
                      window.location.href = row.dataset.href;
                    });
                  });
                });
              </script>


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

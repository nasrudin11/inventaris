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

            <?php
            // Menampilkan pesan sukses
            if (isset($_SESSION['pesan'])) {
                echo "<div class='alert alert-success' id='notification-success'>{$_SESSION['pesan']}</div>";

                echo "<script>
                        setTimeout(function() {
                            document.getElementById('notification-success').remove();
                        }, 3000);
                    </script>";

                unset($_SESSION['pesan']);
            }

            // Menampilkan pesan error
            if (isset($_SESSION['error_message'])) {
                echo "<div class='alert alert-danger' id='notification-error'>{$_SESSION['error_message']}</div>";

                echo "<script>
                        setTimeout(function() {
                            document.getElementById('notification-error').remove();
                        }, 3000);
                    </script>";

                unset($_SESSION['error_message']);
            }
            ?>

            <div class="text-end mb-3">
                <a href="riwayat_main.php" class="btn btn-secondary">Riwayat</a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPeminjaman">Maintenance Baru</button>
            </div>

              <!-- Modal Tambah Barang -->
              <div class="modal fade" id="modalPeminjaman" tabindex="-1" aria-labelledby="modalMaintenanceLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="modalMaintenanceLabel">Maintenance Baru</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              <!-- Form tambah barang -->
                              <form action="../../controller/tambah_maintenance.php" method="POST">
                                  <!-- Input untuk id_barang -->
                                  <div class="mb-3">
                                    <div class="row">
                                      <div class="col">
                                        <label for="idBarang" class="form-label">Nama barang</label>
                                        <select class="form-control" id="id_barang" name="id_barang">
                                            <?php
                                            $sql = "SELECT id_barang, nama_barang FROM barang WHERE id_user = {$_SESSION['id']}";
                                            $result = $koneksi->query($sql);

                                            if ($result->num_rows > 0) {
                                                while($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . $row['id_barang'] . '">' . $row['nama_barang'] . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">No items found</option>';
                                            }
                                            ?>
                                        </select>
                                      </div>
                                      <div class="col">
                                        <label for="jumlah" class="form-label">Jumlah</label>
                                        <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah" required>
                                      </div>
                                    </div>
                                  </div>
                                  <!-- Tombol untuk menyimpan data -->
                                  <button type="submit" class="btn btn-primary">Simpan</button>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>id barang</th>
                                <th>nama Barang</th>
                                <th>jumlah</th>
                                <th>tgl maintenance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                          <?php
                            $query = "SELECT m.*, b.*
                            FROM maintenance m
                            JOIN user u ON m.id_user = u.id_user
                            JOIN barang b ON m.id_barang = b.id_barang
                            WHERE u.id_user = '{$_SESSION['id']}' AND m.status_maintenance = 'maintenance'
                            ORDER BY m.id_maintenance DESC";
                            $result = $koneksi->query($query);

                            if ($result && $result->num_rows > 0) {
                                $no = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $no . "</td>";
                                    echo "<td>" . $row['id_barang'] . "</td>";
                                    echo "<td>" . $row['nama_barang'] . "</td>";
                                    echo "<td>" . $row['jumlah'] . "</td>";
                                    echo "<td>" . date('d F Y', strtotime($row['tgl_maintenance'])) . "</td>";
                                    echo '<td>
                                            <form action="../../controller/return_maintenance.php" method="POST">
                                                <input type="hidden" name="id_maintenance" value="'.$row['id_maintenance'].'">
                                                <input type="hidden" name="jumlah" value="'.$row['jumlah'].'">
                                                <button type="submit" class="btn btn-primary" style="background-color: #3ac7c0; border:none" name="return_maintenance">
                                                    Return
                                                </button>
                                            </form>
                                          </td>';
                                    echo "</tr>";
                                    $no++;

                                }
                            } else {
                                echo "<tr><td colspan='7'>Tidak ada data barang maintenance</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
              </div>
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

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
              <div class="row mb-3">

                <div class="col">
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="peminjaman.php">Peminjaman</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Riwayat</li>
                    </ol>
                  </nav>
                </div>

                <div class="col text-end">
                  <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#cetakModal">Cetak</button>
                </div>
              </div>

            <!-- Tambahkan modal -->
            <div class="modal fade" id="cetakModal" tabindex="-1" aria-labelledby="cetakModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="cetakModalLabel">Cetak Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="cetak_pem.php" method="POST" >
                      <div class="form-group">
                        <label for="bulan">Rentang Bulan</label>
                        <div class="row">
                          <div class="col">
                            <select class="form-control" id="bulan_awal" name="bulan_awal">
                              <option value="1">Januari</option>
                              <option value="2">Februari</option>
                              <option value="3">Maret</option>
                              <option value="4">April</option>
                              <option value="5">Mei</option>
                              <option value="6">Juni</option>
                              <option value="7">Juli</option>
                              <option value="8">Agustus</option>
                              <option value="9">September</option>
                              <option value="10">Oktober</option>
                              <option value="11">November</option>
                              <option value="12">Desember</option>
                            </select>
                          </div>
                          <div class="col">
                            <select class="form-control" id="bulan_akhir" name="bulan_akhir">
                              <option value="1">Januari</option>
                              <option value="2">Februari</option>
                              <option value="3">Maret</option>
                              <option value="4">April</option>
                              <option value="5">Mei</option>
                              <option value="6">Juni</option>
                              <option value="7">Juli</option>
                              <option value="8">Agustus</option>
                              <option value="9">September</option>
                              <option value="10">Oktober</option>
                              <option value="11">November</option>
                              <option value="12">Desember</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="number" class="form-control" id="tahun" name="tahun" value="<?php echo date('Y'); ?>" min="1900" max="2099" required>
                      </div>
                      <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">Cetak</button>
                      </div>
                      
                    </form>
                  </div>
                </div>
              </div>
            </div>

          <script>
              document.addEventListener("DOMContentLoaded", function() {
              // Set nilai default untuk bulan dan tahun
              document.getElementById('bulan_awal').value = new Date().getMonth() + 1; // +1 karena bulan dimulai dari 0
              document.getElementById('bulan_akhir').value = new Date().getMonth() + 1;
            });
          </script>






            

              <div class="card">
                <div class="table-responsive text-nowrap">
                  <table class="table table-striped table-hover" id="peminjamanTable">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Jumlah Barang</th>
                        <th>Tgl peminjaman</th>
                        <th>Tgl pengembalian</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        $query = " SELECT p.*, SUM(pd.jumlah) AS total_jumlah
                        FROM peminjaman p
                        JOIN peminjaman_dtl pd ON p.id_peminjaman = pd.id_peminjaman
                        WHERE p.id_user = '{$_SESSION['id']}' AND p.status = 'dikembalikan'
                        GROUP BY p.id_peminjaman DESC";
                        $result = $koneksi->query($query);

                        if ($result && $result->num_rows > 0) {
                          $no = 1;
                          while ($row = $result->fetch_assoc()) {
                            echo "<tr class='clickable-row' data-href='riwayat_pem_dtl.php?id_peminjaman={$row['id_peminjaman']}'>";
                            echo "<td>" . $no . "</td>";
                            echo "<td>" . $row['nama_peminjam'] . "</td>";
                            echo "<td>" . $row['total_jumlah'] . "</td>";
                            echo "<td>" . $row['tgl_peminjaman'] . "</td>";
                            echo "<td>" . $row['tgl_pengembalian'] . "</td>";
                            echo "</tr>";
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
                  const rows = document.querySelectorAll("#peminjamanTable .clickable-row");
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

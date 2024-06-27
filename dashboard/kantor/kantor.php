<?php
session_start();
include '../../controller/koneksi.php';

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $id_user = $_SESSION['id'];

    $sql = "SELECT b.*, GROUP_CONCAT(s.nama_status SEPARATOR ', ') AS statuses FROM barang b 
    LEFT JOIN status_barang s ON b.id_barang = s.id_barang 
    WHERE b.id_user = '$id_user' AND (b.nama_barang LIKE '%$query%' OR b.id_barang LIKE '%$query%') 
    GROUP BY b.id_barang
    ORDER BY b.nama_barang";
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows > 0) {
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no . "</td>";
            echo "<td>" . $row['nama_barang'] . "</td>";
            echo "<td>" . $row['kategori'] . "</td>";
            echo "<td>" . $row['stok'] . "</td>";
            echo "<td>" . $row['lokasi'] . "</td>";
            echo '<td>
                      <div class="dropdown">
                          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                          </button>
                          <div class="dropdown-menu">
                              <a class="dropdown-item" href="kantor_detail.php?id_barang='.$row['id_barang'].'" >
                                  <i class="bx bx-detail me-1"></i> Detail
                              </a>
                              <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEditBarang" onclick="edit_barang('.$row['id_barang'].')">
                                  <i class="bx bx-edit-alt me-1"></i> Edit
                              </a>
                              <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalTambahStok" onclick="tambah_stok('.$row['id_barang'].')">
                                  <i class="bx bx-plus-circle me-1"></i> Tambah Stok
                              </a>
                              <button type="button" class="dropdown-item" onclick="hapus_barang('. $row['id_barang'] .')">
                                <i class="bx bx-trash me-1"></i> Delete
                              </button>
                          </div>
                        </div>
                      </td>';
            echo "</tr>";
            $no++;
        }
    } else {
        echo "<tr><td colspan='8'>Tidak ada data barang</td></tr>";
    }
    exit;
}

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
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum=1.0"
    />
    <title>Dashboard - Kantor</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap"
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

    <!-- Config -->
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

              <!-- Pencarian -->
              <div class="row mb-3">
                <div class="col">
                  <input type="text" id="search" class="form-control" placeholder="Cari berdasarkan nama atau ID barang...">
                </div>
                <div class="col text-end">
                  <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalImport">Impor Data</button>
                  <a href="../../controller/export.php" class="btn btn-success">Export Data</a>
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">Tambah Barang</button>
                </div>
              </div>

              <!-- Modal import data -->
              <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalImportLabel">Import Data</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form  action="../../controller/import.php" method="POST" enctype="multipart/form-data">
                        <input class="form-control" type="file" name="file_excel" accept=".xlsx,.xls">
                        <button type="submit" class="btn btn-primary mt-3" name="Import">Import</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Modal Tambah Barang -->
              <div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-labelledby="modalTambahBarangLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalTambahBarangLabel">Tambah Barang</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form action="../../controller/tambah_barang.php" method="POST">
                        <!-- Input untuk nama barang -->
                        <div class="mb-3">
                          <label for="namaBarang" class="form-label">Nama Barang</label>
                          <input type="text" class="form-control" id="namaBarang" name="nama_barang" placeholder="Nama Barang" required>
                        </div>
                        <!-- Input untuk kategori -->
                        <div class="mb-3">
                          <div class="row">
                            <div class="col">
                              <label for="kategori" class="form-label">Kategori</label>
                              <input type="text" class="form-control" id="kategori" name="kategori" placeholder="Kategori" required>
                            </div>
                            <div class="col">
                              <label for="stok" class="form-label">Stok</label>
                              <input type="number" class="form-control" id="stok" name="stok" placeholder="Stok" required>
                            </div>
                          </div>
                        </div>
                        <!-- Input untuk Lokasi -->
                        <div class="mb-3">
                          <label for="lokasi" class="form-label">Lokasi</label>
                          <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi" required>
                          <input type="hidden" class="form-control" id="harga" name="harga" value="0">
                        </div>
                        <!-- Tombol untuk menyimpan data -->
                        <button type="submit" class="btn btn-primary">Simpan</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Tabel Barang -->
              <div class="card">
                <div class="table-responsive text-nowrap">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Lokasi</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0" id="table-body">
                      <!-- Data akan dimuat di sini oleh AJAX -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Modal Edit Barang -->
            <div class="modal fade" id="modalEditBarang" tabindex="-1" aria-labelledby="modalEditBarangLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalEditBarangLabel">Edit Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="editBarangForm" action="../../controller/edit_barang.php" method="POST">
                      <input type="hidden" id="edit_id_barang" name="id_barang">
                      <div class="mb-3">
                        <label for="edit_namaBarang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="edit_namaBarang" name="nama_barang" placeholder="Nama Barang" required>
                      </div>
                      <div class="row mb-3">
                        <div class="col">
                          <label for="edit_kategori" class="form-label">Kategori</label>
                          <input type="text" class="form-control" id="edit_kategori" name="kategori" placeholder="Kategori" required>
                        </div>
                        <div class="col">
                          <label for="edit_lokasi" class="form-label">Lokasi</label>
                          <input type="text" class="form-control" id="edit_lokasi" name="lokasi" placeholder="Lokasi" required>
                        </div>
                      </div>
                      <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>


            <!-- Modal Tambah Stok -->
            <div class="modal fade" id="modalTambahStok" tabindex="-1" aria-labelledby="modalTambahStokLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahStokLabel">Tambah Stok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="../../controller/stok_barang.php" method="POST">
                      <input type="hidden" id="tambah_stok_id_barang" name="id_barang">
                      <div class="mb-3">
                        <label for="tambah_stok" class="form-label">Jumlah Stok</label>
                        <input type="number" class="form-control" id="tambah_stok" name="jumlah_stok" placeholder="Jumlah Stok" required>
                      </div>
                      <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal Hapus Barang -->
            <div class="modal fade" id="modalHapusBarang" tabindex="-1" aria-labelledby="modalHapusBarangLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalHapusBarangLabel">Hapus Barang</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <p>Apakah anda yakin? Semua data yang berhubungan akan ikut terhapus</p>
                    </div>
                    <div class="modal-footer">
                      <form id="formHapusBarang" action="../../controller/hapus_barang.php" method="POST">
                        <input type="hidden" id="hapus_id_barang" name="id_barang">
                        <button type="submit" class="btn btn-danger">Konfirmasi Hapus</button>
                      </form>
                    </div>
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
    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../assets/vendor/js/menu.js"></script>

    <!-- Vendors JS -->
    <script src="../../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../../assets/js/dashboards-analytics.js"></script>

    <!-- AJAX untuk pencarian -->
    <script>
      $(document).ready(function() {
          function fetch_data(query = '') {
              $.ajax({
                  url: "kantor.php",
                  method: "POST",
                  data: {query: query},
                  success: function(data) {
                      $('#table-body').html(data);
                  }
              });
          }

          $('#search').on('keyup', function() {
              var query = $(this).val();
              fetch_data(query);
          });

          fetch_data();
      });

        function edit_barang(id_barang) {
        $.ajax({
          url: '../../controller/get_barang.php',
          method: 'POST',
          dataType: 'json',
          data: { id_barang: id_barang },
          success: function(response) {
            $('#edit_id_barang').val(response.id_barang);
            $('#edit_namaBarang').val(response.nama_barang);
            $('#edit_kategori').val(response.kategori);
            $('#edit_lokasi').val(response.lokasi);
          }
        });
      }

      function tambah_stok(id_barang) {
        $('#tambah_stok_id_barang').val(id_barang);
      }
      
      // Fungsi untuk menampilkan modal konfirmasi penghapusan
      function hapus_barang(id_barang) {
        $('#hapus_id_barang').val(id_barang);
        $('#modalHapusBarang').modal('show');
      }

    </script>
  </body>
</html>

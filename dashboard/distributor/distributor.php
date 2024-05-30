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

            <div class="text-end mb-3">
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalImport">Impor Data</button>
                <a href="../../controller/export.php" class="btn btn-success">Export Data</a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">Tambah Barang</button>
            </div>


              <!-- Modal import data -->
              <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahBarangLabel">Import Data</h5>
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
                              <!-- Form tambah barang -->
                              <form action="../../controller/tambah_barang.php" method="POST">
                                  <!-- Input untuk nama barang -->
                                  <div class="mb-3">
                                      <label for="namaBarang" class="form-label">Nama Barang</label>
                                      <input type="text" class="form-control" id="namaBarang" name="nama_barang" placeholder="Nama Barang">
                                  </div>
                                  <!-- Input untuk kategori -->
                                  <div class="mb-3">
                                    <div class="row">
                                      <div class="col">
                                        <label for="kategori" class="form-label">Kategori</label>
                                        <input type="text" class="form-control" id="kategori" name="kategori" placeholder="Kategori">
                                      </div>
                                      <div class="col">
                                        <label for="stok" class="form-label">Stok</label>
                                        <input type="number" class="form-control" id="stok" name="stok" placeholder="Stok">
                                      </div>
                                    </div>
                                  </div>
                                  <!-- Input untuk Lokasi -->
                                  <div class="mb-3">
                                      <label for="deskripsi" class="form-label">Lokasi</label>
                                      <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi">
                                  </div>
                                  <!-- Input untuk deskripsi -->
                                  <div class="mb-3">
                                      <label for="deskripsi" class="form-label">Deskripsi</label>
                                      <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi"></textarea>
                                  </div>
                                  <!-- Input untuk stok -->
                                  <div class="mb-3">
                                    
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
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Lokasi</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                          <?php
                            $query = "SELECT * FROM barang WHERE id_user = '{$_SESSION['id']}'";
                            $result = $koneksi->query($query);

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
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEdit'.$row['id_barang'].'">
                                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalTambah'.$row['id_barang'].'">
                                                        <i class="bx bx-plus-circle me-1"></i> Tambah Stok
                                                    </a>
                                                    <form action="../../controller/hapus_barang.php" method="POST">
                                                        <input type="hidden" name="id_barang" value="'.$row['id_barang'].'">
                                                        <button type="submit" class="dropdown-item" name="hapus_barang">
                                                            <i class="bx bx-trash me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                              </div>
                                            </td>';
                                    echo "</tr>";
                                    $no++;
                                ?>

                                  <!-- Modal Edit Barang -->
                                  <div class="modal fade" id="modalEdit<?php echo $row['id_barang']; ?>" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="modalEditLabel">Edit Barang</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                          <!-- Form edit barang -->
                                          <form action="../../controller/edit_barang.php" method="POST">
                                            <div class="mb-3">
                                                <label for="editNamaBarang" class="form-label">Nama Barang</label>
                                                <input type="text" class="form-control" id="editNamaBarang" name="nama_barang" value="<?php echo $row['nama_barang']; ?>" placeholder="Nama Barang">
                                            </div>
                                            <div class="row">
                                              <div class="col">
                                                <div class="mb-3">
                                                    <label for="editKategori" class="form-label">Kategori</label>
                                                    <input type="text" class="form-control" id="editKategori" name="kategori" value="<?php echo $row['kategori']; ?>" placeholder="Kategori">
                                                </div>
                                              </div>
                                              <div class="col">
                                                <div class="mb-3">
                                                  <label for="editLokasi" class="form-label">Lokasi</label>
                                                  <input type="text" class="form-control" id="editLokasi" name="lokasi" value="<?php echo $row['lokasi']; ?>" placeholder="Lokasi">
                                                </div>
                                              </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="editDeskripsi" class="form-label">Deskripsi</label>
                                                <textarea class="form-control" id="editDeskripsi" name="deskripsi" rows="3" placeholder="Deskripsi"><?php echo $row['deskripsi']; ?></textarea>
                                            </div>
                                            
                                            <input type="hidden" name="id_barang" value="<?php echo $row['id_barang']; ?>">
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>

                                  <!-- Modal Tambah Stok -->
                                  <div class="modal fade" id="modalTambah<?php echo $row['id_barang']; ?>" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="modalTambahLabel">Tambah Stok - <?php echo $row['nama_barang']; ?></h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                          <!-- Form tambah stok barang -->
                                          <form action="../../controller/stok_barang.php" method="POST">
                                            <!-- Input untuk jumlah stok yang akan ditambahkan -->
                                            <div class="mb-3">
                                              <label for="tambahStok" class="form-label">Jumlah Stok</label>
                                              <input type="number" class="form-control" id="tambahStok" name="jumlah_stok" placeholder="Masukkan jumlah stok yang ditambahkan">
                                            </div>
                                            <input type="hidden" name="id_barang" value="<?php echo $row['id_barang']; ?>">
                                            <!-- Tombol untuk menyimpan perubahan -->
                                            <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  
                                <?php

                                }
                            } else {
                                echo "<tr><td colspan='7'>Tidak ada data barang</td></tr>";
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

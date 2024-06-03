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
                <a href="riwayat_pem.php" class="btn btn-secondary">Riwayat</a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPeminjaman">Peminjaman Baru</button>
            </div>

              <!-- Modal Tambah Barang -->
              <div class="modal fade" id="modalPeminjaman" tabindex="-1" aria-labelledby="modalPeminjamanLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalPeminjamanLabel">Peminjaman Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form tambah barang -->
                            <form action="../../controller/tambah_peminjaman.php" method="POST">
                                <!-- Input untuk nama peminjam -->
                                <div class="mb-3">
                                    <label for="namaPeminjam" class="form-label">Nama Peminjam</label>
                                    <input type="text" class="form-control" id="namaPeminjam" name="nama_peminjam" placeholder="Nama Peminjam" required>
                                </div>
                                <!-- Container untuk id_barang dan jumlah -->
                                <div id="barangContainer">
                                    <div class="mb-3 barang-item">
                                        <div class="row">
                                            <div class="col">
                                                <label for="idBarang" class="form-label">ID Barang</label>
                                                <input type="text" class="form-control" id="idBarang" name="id_barang[]" placeholder="ID Barang" required>
                                            </div>
                                            <div class="col">
                                                <label for="jumlah" class="form-label">Jumlah</label>
                                                <input type="number" class="form-control" id="jumlah" name="jumlah[]" placeholder="Jumlah" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol untuk menambah dan mengurangi id_barang dan jumlah -->
                                <div class="mb-3">
                                  <button type="button" class="btn btn-secondary" id="addBarangButton">Tambah</button>
                                  <button type="button" class="btn btn-danger" id="removeBarangButton">Batal</button>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="row">
                                        <!-- Input untuk tgl peminjaman -->
                                        <div class="col">
                                            <label for="tglPeminjaman" class="form-label">Tanggal Peminjaman</label>
                                            <input type="date" class="form-control" id="tglPeminjaman" name="tgl_peminjaman" required>
                                        </div>
                                        <!-- Input untuk tgl pengembalian -->
                                        <div class="col">
                                            <label for="tglPengembalian" class="form-label">Tanggal Pengembalian</label>
                                            <input type="date" class="form-control" id="tglPengembalian" name="tgl_pengembalian" required>
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

            <script>
                document.getElementById('addBarangButton').addEventListener('click', function() {
                    var barangContainer = document.getElementById('barangContainer');
                    var newBarangItem = document.createElement('div');
                    newBarangItem.className = 'mb-3 barang-item';
                    newBarangItem.innerHTML = `
                        <div class="row">
                            <div class="col">
                                <label for="idBarang" class="form-label">ID Barang</label>
                                <input type="text" class="form-control" name="id_barang[]" placeholder="ID Barang" required>
                            </div>
                            <div class="col">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" name="jumlah[]" placeholder="Jumlah" required>
                            </div>
                        </div>
                    `;
                    barangContainer.appendChild(newBarangItem);
                });

                document.getElementById('removeBarangButton').addEventListener('click', function() {
                    var barangContainer = document.getElementById('barangContainer');
                    var barangItems = barangContainer.getElementsByClassName('barang-item');
                    if (barangItems.length > 1) {
                        barangContainer.removeChild(barangItems[barangItems.length - 1]);
                    }
                });
            </script>

              <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>total</th>
                                <th>tgl peminjaman</th>
                                <th>tgl pengembalian</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                          <?php
                            $query = " SELECT p.*, SUM(pd.jumlah) AS total_jumlah
                            FROM peminjaman p
                            JOIN peminjaman_dtl pd ON p.id_peminjaman = pd.id_peminjaman
                            WHERE p.id_user = '{$_SESSION['id']}' AND p.status = 'dipinjam'
                            GROUP BY p.id_peminjaman DESC";
                            $result = $koneksi->query($query);

                            if ($result && $result->num_rows > 0) {
                                $no = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $no . "</td>";
                                    echo "<td>" . $row['nama_peminjam'] . "</td>";
                                    echo "<td>" . $row['total_jumlah'] . "</td>";
                                    echo "<td>" . $row['tgl_peminjaman'] . "</td>";
                                    echo "<td>" . $row['tgl_pengembalian'] . "</td>";
                                    echo '<td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="peminjaman_dtl.php?id_peminjaman=' . $row['id_peminjaman'] . '">
                                                        <i class="bx bx-edit-alt me-1"></i> Lihat
                                                    </a>
                                                    
                                                    <form action="../../controller/hapus_barang.php" method="POST">
                                                        <input type="hidden" name="id_peminjaman" value="'.$row['id_peminjaman'].'">
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
                                                <label for="editnamaPeminjam" class="form-label">Nama Barang</label>
                                                <input type="text" class="form-control" id="editnamaPeminjam" name="nama_barang" value="<?php echo $row['nama_barang']; ?>" placeholder="Nama Barang">
                                            </div>
                                            <div class="row">
                                              <div class="col">
                                                <div class="mb-3">
                                                    <label for="editid_barang" class="form-label">id_barang</label>
                                                    <input type="text" class="form-control" id="editid_barang" name="id_barang" value="<?php echo $row['id_barang']; ?>" placeholder="id_barang">
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

                                  <!-- Modal Tambah jumlah -->
                                  <div class="modal fade" id="modalTambah<?php echo $row['id_barang']; ?>" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="modalTambahLabel">Tambah jumlah - <?php echo $row['nama_barang']; ?></h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                          <!-- Form tambah jumlah barang -->
                                          <form action="../../controller/jumlah_barang.php" method="POST">
                                            <!-- Input untuk jumlah jumlah yang akan ditambahkan -->
                                            <div class="mb-3">
                                              <label for="tambahjumlah" class="form-label">Jumlah jumlah</label>
                                              <input type="number" class="form-control" id="tambahjumlah" name="jumlah_jumlah" placeholder="Masukkan jumlah jumlah yang ditambahkan">
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
                                echo "<tr><td colspan='7'>Tidak ada data pemminjaman barang</td></tr>";
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

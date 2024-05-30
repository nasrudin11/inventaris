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
            <div class="container flex-grow-1 container-p-y">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="peminjaman.php">Peminjaman</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>

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


            <?php
                $query = "SELECT pd.*, b.nama_barang, b.id_barang, b.status_barang, p.id_peminjaman, p.nama_peminjam
                FROM peminjaman_dtl pd 
                JOIN barang b ON pd.id_barang = b.id_barang 
                JOIN peminjaman p ON p.id_peminjaman = pd.id_peminjaman
                WHERE pd.id_peminjaman = {$_GET['id_peminjaman']}";
                $result = $koneksi->query($query);
            
                $row = $result->fetch_assoc();
                $no = 1;
            ?>

                <div class="card mb-4">
                    <div class="table-responsive text-nowrap">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>ID Barang.</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['id_barang'] ?></td>
                                    <td><?php echo $row['nama_barang'] ?></td>
                                    <td><?php echo $row['jumlah'] ?></td>
                                    <td>
                                        <select class="form-select" id="exampleFormControlSelect1" >
                                            <option selected><?php echo $row['status_barang']; ?></option>
                                            <option value="1">Baik</option>
                                            <option value="1">Rusak</option>
                                        </select>
                                    </td>
                                    <td>
                                      <?php if($row['status']=='dipinjam'){ ?>

                                        <form action="../../controller/return_peminjaman.php" method="POST">
                                          <input type="hidden" name="id_peminjaman" value="<?php echo $row['id_peminjaman']?>">
                                          <input type="hidden" name="id_peminjaman_dtl" value="<?php echo $row['id_peminjaman_dtl']?>">
                                          <button type="submit" class="btn btn-primary" style="background-color: #3ac7c0; border:none" name="submit"> Return</button>
                                        </form>

                                      <?php
                                        }else{
                                          echo " <button class='btn btn-primary' style='background-color: #3ac7c0; border:none' name='submit' disabled>Returned</button>";
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
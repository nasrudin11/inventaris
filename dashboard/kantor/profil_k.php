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


            <?php
                $query = "SELECT * FROM profil WHERE id_user = '{$_SESSION['id']}'";
                $result = $koneksi->query($query);
            
                $row = $result->fetch_assoc();
            ?>
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="mb-0">Profile Company</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 d-flex justify-content-center">
                                <div class="text-center">
                                    <?php if (!empty($row['gambar_profil'])) : ?>
                                        <img src="../../img/upload/profil/<?php echo $row['gambar_profil']; ?>" class="border border-primary rounded-circle" alt="Company Logo" style="width: 180px; height: 180px;">
                                    <?php else : ?>
                                        <img src="../../img/user.png" class="border border-primary rounded-circle" alt="Company Logo" style="width: 180px; height: 180px;">
                                    <?php endif; ?>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#gambarModal">Ganti</button>
                                    </div>
                                </div>
                            </div>


                            <!-- Modal ganti foto profil -->
                            <div class="modal fade" id="gambarModal" tabindex="-1" aria-labelledby="gambarModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalTambahBarangLabel">Ganti Foto Profil</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Form tambah barang -->
                                            <form  action="../../controller/edit_profil.php" method="POST" enctype="multipart/form-data">
                                                <input class="form-control" type="file" id="formFile" name="gambar_profil"/>
                                                <button type="submit" class="btn btn-primary mt-3" name="edit_gambar_profil">Upload</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="col-md-9">
                                <form action="../../controller/edit_profil.php" method="POST">
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label" for="basic-icon-default-company">Company</label>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-merge">
                                            <span id="basic-icon-default-company2" class="input-group-text"
                                                ><i class="bx bx-buildings"></i
                                            ></span>
                                            <input
                                                type="text"
                                                id="basic-icon-default-company"
                                                class="form-control"
                                                aria-describedby="basic-icon-default-company2"
                                                value="<?php echo $row['nama']; ?>"
                                                name="nama"
                                            />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label" for="basic-icon-default-email">Email</label>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                            <input
                                                type="text"
                                                id="basic-icon-default-email"
                                                class="form-control"
                                                aria-describedby="basic-icon-default-email2"
                                                value="<?php echo $_SESSION['email']; ?>"
                                                name="email"
                                            />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 form-label" for="basic-icon-default-phone">Phone No</label>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-merge">
                                            <span id="basic-icon-default-phone2" class="input-group-text"
                                                ><i class="bx bx-phone"></i
                                            ></span>
                                            <input
                                                type="text"
                                                id="basic-icon-default-phone"
                                                class="form-control phone-mask"
                                                aria-describedby="basic-icon-default-phone2"
                                                value="<?php echo $row['no_tlp']; ?>"
                                                name="no_tlp"
                                            />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 form-label" for="basic-icon-default-phone">Address</label>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-merge">
                                            <span id="basic-icon-default-phone2" class="input-group-text"
                                                ><i class="bx bx-map"></i
                                            ></span>
                                            <input
                                                type="text"
                                                id="basic-icon-default-phone"
                                                class="form-control phone-mask"
                                                aria-describedby="basic-icon-default-phone2"
                                                value="<?php echo $row['alamat']; ?>"
                                                name="alamat"
                                            />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 form-label" for="basic-icon-default-message">Description</label>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-merge">
                                            <span id="basic-icon-default-message2" class="input-group-text"
                                                ><i class="bx bx-comment"></i
                                            ></span>
                                            <textarea
                                                id="basic-icon-default-message"
                                                class="form-control"
                                                aria-describedby="basic-icon-default-message2"
                                                name="deskripsi"
                                            ><?php echo $row['deskripsi']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row text-end">
                                        <div class="col">
                                            <button type="submit" class="btn btn-primary" name="edit_profil">Update</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
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
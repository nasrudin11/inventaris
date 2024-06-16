<?php
    session_start();
    include '../../controller/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Dashboard - Kantor</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Icons -->
    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/apex-charts/apex-charts.css" />
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
            <?php include '../../partials/aside.php'; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php include '../../partials/navbar.php'; ?>
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
                            $query = "SELECT p.*, d.*, b.nama_barang, pr.nama
                            FROM pemesanan p
                            JOIN pemesanan_dtl d ON p.id_pemesanan = d.id_pemesanan
                            JOIN barang b ON d.id_barang = b.id_barang
                            JOIN user u ON u.id_user = p.id_user
                            JOIN profil pr ON pr.id_user = u.id_user
                            WHERE p.id_user2 = {$_SESSION['id']}
                            ORDER BY p.id_pemesanan DESC, d.id_barang";
                        
                            $result = $koneksi->query($query);
                            $pemesananData = [];
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $pemesananData[$row['id_pemesanan']]['details'][] = $row;
                                    $pemesananData[$row['id_pemesanan']]['nama'] = $row['nama'];
                                    $pemesananData[$row['id_pemesanan']]['tgl_pemesanan'] = $row['tgl_pemesanan'];
                                    $pemesananData[$row['id_pemesanan']]['total_harga'] = $row['total_harga'];
                                }
                            }
                        ?>
                        <div class="accordion shadow" id="accordionExample">
                            <?php foreach ($pemesananData as $id_pemesanan => $data): ?>
                                <div class="accordion-item border">
                                    <h2 class="accordion-header" id="heading-<?php echo $id_pemesanan; ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $id_pemesanan; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $id_pemesanan; ?>"> 
                                            <div class="w-100"><strong><?php echo $data['nama'] ?></strong> - Pesanan Baru</div> 
                                            <div>
                                                <span style="font-size: 12px;"><?php echo date('d M Y', strtotime($data["tgl_pemesanan"])); ?> </span>
                                            </div> 
                                        </button>
                                    </h2>
                                    <div id="collapse-<?php echo $id_pemesanan; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $id_pemesanan; ?>" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Barang</th>
                                                        <th>Jumlah</th>
                                                        <th>Harga</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($data['details'] as $detail): ?>
                                                        <tr>
                                                            <td><?php echo $detail['nama_barang']; ?></td>
                                                            <td><?php echo $detail['jumlah']; ?></td>
                                                            <td><?php echo $detail['harga']; ?></td>
                                                            <td><?php echo "Rp " . $detail['harga'] * $detail['jumlah']; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>   
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><strong>Jumlah Total:</strong></td>
                                                        <td><strong>Rp <?php echo $data['total_harga'] ?></strong></td>
                                                    </tr>                  
                                                </tbody>
                                            </table>
                                            <div class="text-end">   
                                                <?php if ($detail['status'] == 'menunggu') { ?>
                                                    <form action="../../controller/konfirmasi_order.php" method="POST">
                                                        <input type="hidden" name="id_pemesanan" value="<?php echo $detail['id_pemesanan']; ?>">
                                                        <button type="submit" class="btn btn-primary" style="background-color: #3ac7c0; border:none">Konfirmasi</button>
                                                    </form>  
                                                <?php } else { ?>
                                                    <button class="btn btn-primary" style="background-color: #3ac7c0; border:none" name="submit" disabled>Konfirmasi</button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
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

<?php
    session_start();
    include '../../controller/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Nota Pembelian</title>
    <meta name="description" content="" />
    <style>
        .table-no-border {
            border-collapse: collapse;
            width: 100%;
        }
        .table-no-border th, .table-no-border td {
            border: none;
            padding: 8px;
            text-align: left;
        }
    </style>
    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/apex-charts/apex-charts.css" />
    <script src="../../assets/vendor/js/helpers.js"></script>
    <script src="../../assets/js/config.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center">Nota Pembelian</h3>
                <table class='table table-no-border mt-3'>
                    <thead>
                        <tr>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                   $query = "SELECT p.*, pd.*, b.nama_barang, b.id_barang
                             FROM pemesanan_dtl pd
                             JOIN pemesanan p ON p.id_pemesanan = pd.id_pemesanan
                             JOIN barang b ON pd.id_barang = b.id_barang
                             WHERE p.id_user = '{$_SESSION['id']}' AND pd.id_pemesanan = {$_GET['id']}";
                    $result = $koneksi->query($query);

                    if ($result && $result->num_rows > 0) {
                        $total = 0;
                        while ($row = $result->fetch_assoc()) {
                            $subtotal = $row['jumlah'] * $row['harga'];
                            echo "<tr>
                                    <td>{$row['id_barang']}</td>
                                    <td>{$row['nama_barang']}</td>
                                    <td>{$row['jumlah']}</td>
                                    <td>Rp " . number_format($row['harga'], 2, ',', '.') . "</td>
                                    <td>Rp " . number_format($subtotal, 2, ',', '.') . "</td>
                                  </tr>";
                            $total += $subtotal;
                        }
                        
                        echo "</tbody></table>";
                        echo "<hr>
                            <div class='row'>
                                <div class='col-md-9 text-end'><strong>Jumlah Total:</strong></div>
                                <div class='col-md-2'><strong>Rp " . number_format($total, 2, ',', '.') . "</strong></div>
                            </div>";
                    } else {
                        echo "<p class='text-center'>Tidak ada data ditemukan.</p>";
                    }
                ?>
                
                <div class="text-center mt-3">
                    <button onclick="window.print()" class="btn btn-primary">Cetak</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../assets/vendor/js/menu.js"></script>
    <script src="../../assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script src="../../assets/js/dashboards-analytics.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>                   
</body>
</html>

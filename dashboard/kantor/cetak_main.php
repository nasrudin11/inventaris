<?php
session_start();
include '../../controller/koneksi.php';
require '../../vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

// Tangkap data dari form modal
$bulan_awal = $_POST['bulan_awal'];
$bulan_akhir = $_POST['bulan_akhir'];
$tahun = $_POST['tahun'];

// Query untuk mengambil data maintenance sesuai dengan rentang bulan dan tahun yang dipilih
$query = "SELECT m.*, b.*
          FROM maintenance m
          JOIN user u ON m.id_user = u.id_user
          JOIN barang b ON m.id_barang = b.id_barang
          WHERE u.id_user = '{$_SESSION['id']}' 
          AND m.status_maintenance = 'dikembalikan'
          AND MONTH(m.tgl_maintenance) BETWEEN $bulan_awal AND $bulan_akhir
          AND YEAR(m.tgl_maintenance) = $tahun";

$result = $koneksi->query($query);

// Generate HTML untuk PDF
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Maintenance</title>
    <style>
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Riwayat Maintenance</h1>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tgl Maintenance</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $no . "</td>";
                    echo "<td>" . $row['id_barang'] . "</td>";
                    echo "<td>" . $row['nama_barang'] . "</td>";
                    echo "<td>" . $row['jumlah'] . "</td>";
                    echo "<td>" . date('d M Y', strtotime($row['tgl_maintenance'])) . "</td>";
                    echo "<td>" . date('d M Y', strtotime($row['tgl_main_kembali'])) . "</td>";
                    echo "<td>Returned</td>";
                    echo "</tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='7'>Tidak ada data maintenance dalam rentang waktu yang dipilih</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php
$html = ob_get_clean();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("riwayat_maintenance.pdf", array("Attachment" => 0));
?>

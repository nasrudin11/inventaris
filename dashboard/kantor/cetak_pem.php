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



$query = "SELECT p.*, pd.jumlah, b.nama_barang, pd.tgl_pengembalian_dtl, 
            (SELECT SUM(jumlah) 
            FROM peminjaman_dtl 
            WHERE id_peminjaman = p.id_peminjaman) as total_jumlah
        FROM peminjaman p
        JOIN peminjaman_dtl pd ON p.id_peminjaman = pd.id_peminjaman
        JOIN barang b ON pd.id_barang = b.id_barang
        WHERE p.id_user = '{$_SESSION['id']}' AND p.status = 'dikembalikan'
        AND MONTH(p.tgl_peminjaman) >= $bulan_awal AND MONTH(p.tgl_peminjaman) <= $bulan_akhir
        AND YEAR(p.tgl_peminjaman) = $tahun
        ORDER BY p.id_peminjaman, b.nama_barang";


$result = $koneksi->query($query);

$dompdf = new Dompdf();

ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman</title>
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
    <h1>Riwayat Peminjaman</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Total Jumlah</th>
                <th>Durasi Peminjaman</th>
                <th>Tgl Pengembalian</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 1;
            $current_id = '';
            $rowspan = [];
            $data = [];

            // Mengumpulkan data dan menghitung rowspan
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id_peminjaman'];
                    if (!isset($data[$id])) {
                        $data[$id] = [];
                        $rowspan[$id] = 0;
                    }
                    $data[$id][] = $row;
                    $rowspan[$id]++;
                }
            }

            // Menampilkan data dengan merge cells
            foreach ($data as $id => $rows) {
                $first = true;
                foreach ($rows as $row) {
                    echo "<tr>";
                    if ($first) {
                        echo "<td rowspan='{$rowspan[$id]}'>" . $counter++ . "</td>";
                        echo "<td rowspan='{$rowspan[$id]}'>" . $row['nama_peminjam'] . "</td>";
                        echo "<td>" . $row['nama_barang'] . "</td>";
                        echo "<td>" . $row['jumlah'] . "</td>";
                        echo "<td rowspan='{$rowspan[$id]}'>" . $row['total_jumlah'] . "</td>";
                        echo "<td rowspan='{$rowspan[$id]}'>" . date('d M Y', strtotime($row['tgl_peminjaman'])) . " - " . date('d M Y', strtotime($row['tgl_pengembalian'])) . "</td>";
                        echo "<td>" . date('d M Y', strtotime($row['tgl_pengembalian_dtl'])) . "</td>";
                        $first = false;
                    } else {
                        echo "<td>" . $row['nama_barang'] . "</td>";
                        echo "<td>" . $row['jumlah'] . "</td>";
                        echo "<td>" . date('d M Y', strtotime($row['tgl_pengembalian_dtl'])) . "</td>";
                    }
                    echo "</tr>";
                }
            }

            if (empty($data)) {
                echo "<tr><td colspan='7'>Tidak ada data peminjaman barang</td></tr>";
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
$dompdf->stream("riwayat_peminjaman.pdf", array("Attachment" => 0));
?>

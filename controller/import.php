<?php
include "koneksi.php";
session_start();

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["file_excel"])) { // Periksa apakah file telah diunggah
        $file_extension = pathinfo($_FILES["file_excel"]["name"], PATHINFO_EXTENSION);
        if ($file_extension == "xlsx" || $file_extension == "xls") {
            $file_tmp = $_FILES["file_excel"]["tmp_name"];

            // Baca file Excel
            $spreadsheet = IOFactory::load($file_tmp);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $firstRowSkipped = false;

            foreach ($sheetData as $row) {
                if (!$firstRowSkipped) {
                    $firstRowSkipped = true;
                    continue;
                }

                $nama = $row['A'];
                $kategori = $row['B'];
                $stok = $row['C'];
                $lokasi = $row['D'];
                $deskripsi = $row['E'];

                $query = "INSERT INTO barang (id_user, nama_barang, kategori, stok, lokasi, deskripsi) VALUES ({$_SESSION['id']}, '$nama', '$kategori', '$stok', '$lokasi', '$deskripsi')";
                $result = $koneksi->query($query);
            }
            $_SESSION['pesan'] = "Data Excel berhasil di import ke database";

            if($_SESSION['role'] == "Inventaris Kantor"){
                header("Location: ../dashboard/kantor/kantor.php");
            }else{
                header("Location: ../dashboard/distributor/distributor.php");
            }
            exit();
        } else {
            echo "Hanya file Excel yang diizinkan.";
        }
    } else {
        echo "Tidak ada file yang diunggah.";
    }
}
?>

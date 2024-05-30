<?php
include "koneksi.php";
session_start();

// Ambil data barang dari database
$query = "SELECT * FROM barang WHERE id_user = {$_SESSION["id"]}";
$result = $koneksi->query($query);

// Buat header file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=data_barang.xls");

// Buat header baris Excel
echo "Nama Barang\tKategori\tStok\tLokasi\tDeskripsi\n";

// Isi data barang
while ($row = $result->fetch_assoc()) {
    echo $$row['nama_barang'] . "\t" . $row['kategori'] . "\t" . $row['stok'] . "\t" . $row['lokasi'] . "\t" . $row['deskripsi']  ."\n";
}


$koneksi->close();
?>

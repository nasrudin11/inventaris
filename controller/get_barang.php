<?php
session_start();
include 'koneksi.php'; // Sesuaikan dengan path yang benar

if(isset($_POST['id_barang'])) {
    $id_barang = $_POST['id_barang'];

    // Query untuk mengambil data barang berdasarkan ID barang
    $sql = "SELECT * FROM barang WHERE id_barang = '$id_barang'";
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Mengembalikan data dalam format JSON
        echo json_encode($row);
    } else {
        // Jika tidak ada data, kirim respons kosong
        echo json_encode(array());
    }
} else {
    // Jika tidak ada ID barang yang diterima, kirim respons kosong
    echo json_encode(array());
}
?>

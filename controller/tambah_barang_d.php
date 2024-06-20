<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_barang = $_POST["nama_barang"];
    $kategori = $_POST["kategori"];
    $stok = $_POST["stok"];
    $harga = $_POST["harga"];
    $lokasi = $_POST["lokasi"];

    // Mendapatkan informasi file gambar yang diunggah
    $nama_file = $_FILES['gambar_barang']['name'];
    $ukuran_file = $_FILES['gambar_barang']['size'];
    $tmp_file = $_FILES['gambar_barang']['tmp_name'];
    $tipe_file = $_FILES['gambar_barang']['type'];

    // Direktori penyimpanan gambar
    $direktori = "../img/upload/barang/";

    // Membuat nama file baru berdasarkan timestamp
    $nama_file_baru = uniqid() . '_' . $nama_file;

    // Memindahkan file yang diunggah ke direktori penyimpanan
    if (move_uploaded_file($tmp_file, $direktori . $nama_file_baru)) {
        // Query untuk menambahkan data barang baru ke database
        $query = "INSERT INTO barang (id_user, nama_barang, kategori, stok, lokasi, harga, gambar_barang) VALUES ({$_SESSION['id']}, '$nama_barang', '$kategori', '$stok', '$lokasi', '$harga', '$nama_file_baru')";

        if ($koneksi->query($query) === TRUE) {
            $_SESSION['pesan'] = "Data barang berhasil ditambahkan.";
            $koneksi->query($query2 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Adding a new item ($nama_barang)')");
            
            header("Location: ../dashboard/distributor/distributor.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error: " . $query . "<br>" . $koneksi->error;
            
            header("Location: ../dashboard/distributor/distributor.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Error uploading file.";
        header("Location: ../dashboard/distributor/distributor.php");
        exit();
    }

    $koneksi->close();
}

?>

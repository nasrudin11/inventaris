<?php
include 'koneksi.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_barang = $_POST["nama_barang"];
    $kategori = $_POST["kategori"];
    $deskripsi = $_POST["deskripsi"];
    $stok = $_POST["stok"];

    $query = "INSERT INTO barang (id_user, nama_barang, kategori, stok, lokasi, deskripsi) VALUES ('{$_SESSION['id']}','$nama_barang', '$kategori', $stok, '$lokasi', '$deskripsi')";

    if ($koneksi->query($query) === TRUE) {
        $_SESSION['pesan'] = "Data barang berhasil ditambahkan.";
        $koneksi->query($query2 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Adding a new item ($nama_barang)')");
        
        if($_SESSION['role'] == "Inventaris Kantor"){
            header("Location: ../dashboard/kantor/kantor.php");
        }else{
            header("Location: ../dashboard/distributor/distributor.php");
        }
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $query . "<br>" . $koneksi->error;
        
        if($_SESSION['role'] == "Inventaris Kantor"){
            header("Location: ../dashboard/kantor/kantor.php");
        }else{
            header("Location: ../dashboard/distributor/distributor.php");
        }
        exit();
    }

    $koneksi->close();
}
?>
<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai input dari form edit barang
    $id_barang = $_POST["id_barang"];
    $nama_barang = $_POST["nama_barang"];
    $kategori = $_POST["kategori"];
    $lokasi = $_POST["lokasi"];
    $harga = $_POST["harga"];

    // Query SQL untuk memperbarui data barang di database
    $query = "UPDATE barang SET nama_barang='$nama_barang', kategori='$kategori', deskripsi='$deskripsi', lokasi='$lokasi', harga='$harga' WHERE id_barang='$id_barang'";

    // Periksa apakah query berhasil dieksekusi
    if ($koneksi->query($query) === TRUE) {
        $_SESSION['pesan'] = "Data barang berhasil diperbarui.";
        $koneksi->query($query2 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Edit the item')");
        if($_SESSION['role'] == "Inventaris Kantor"){
            header("Location: ../dashboard/kantor/kantor.php");
        }else{
            header("Location: ../dashboard/distributor/distributor.php");
        }
        exit();
    } else {
        $_SESSION['pesan'] = "Error: " . $query . "<br>" . $koneksi->error;
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

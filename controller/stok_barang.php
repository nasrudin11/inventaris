<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id_barang"];
    $jumlah_stok = $_POST["jumlah_stok"];

    $query = "UPDATE barang SET stok = stok + $jumlah_stok WHERE id_barang = $id";

    if ($koneksi->query($query) === TRUE) {
        // Mendapatkan nama barang dari tabel inventaris
        $querybarang = "SELECT nama_barang FROM barang WHERE id_barang = $id";
        $resultbarang = $koneksi->query($querybarang);
        
        if ($resultbarang && $resultbarang->num_rows > 0) {
            $rowbarang = $resultbarang->fetch_assoc();
            $nama_barang = $rowbarang["nama_barang"];
            
            $query2 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Adding stock of item $nama_barang ($jumlah_stok) ')";
            
            if ($koneksi->query($query2) === TRUE) {
                $_SESSION['pesan'] = "Stok barang berhasil ditambahkan.";
            } else {
                $_SESSION['pesan'] = "Error: " . $query2 . "<br>" . $koneksi->error;
            }
        } else {
            $_SESSION['pesan'] = "Nama barang tidak ditemukan.";
        }
    } else {
        $_SESSION['pesan'] = "Error: " . $query . "<br>" . $koneksi->error;
    }

    if($_SESSION['role'] == "Inventaris Kantor"){
        header("Location: ../dashboard/kantor/kantor.php");
    }else{
        header("Location: ../dashboard/distributor/distributor.php");
    }
    exit();
}

?>

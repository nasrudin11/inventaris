<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_barang = $_POST["id_barang"];

    $query = "DELETE FROM barang WHERE id_barang = '$id_barang'";

    if ($koneksi->query($query) === TRUE) {
        $_SESSION['pesan'] = "Barang berhasil dihapus.";
        
        $koneksi->query($query2 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Deleting an item')");
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

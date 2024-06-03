<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_maintenance = $_POST["id_peminjaman"];

    $query = "UPDATE maintenance SET status_maintenance = 'dikembalikan' WHERE id_maintenance = '$id_maintenance'";

    if ($koneksi->query($query) === TRUE) {
        $_SESSION['pesan'] = "Data barang berhasil dikembalikan.";
        $koneksi->query($query2 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Return the loans item')");

        header("Location: ../dashboard/kantor/maintenance.php");
        exit();
    } else {    
        $_SESSION['pesan'] = "Error: " . $query . "<br>" . $koneksi->error;

        header("Location: ../dashboard/kantor/maintenance.php");

        exit();
    }

    $koneksi->close();
}
?>

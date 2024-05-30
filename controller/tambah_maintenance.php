<?php
include 'koneksi.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_barang = $_POST["id_barang"];
    $jumlah = $_POST['jumlah'];

    $query = "INSERT INTO maintenance (id_user, id_barang, jumlah) VALUES ('{$_SESSION['id']}', '$id_barang', '$jumlah')";

    if ($koneksi->query($query) === TRUE) {
        
        $koneksi->query("INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Adding a new maintenance ()')");
        $_SESSION['pesan'] = "Data barang berhasil ditambahkan.";
        
        header("Location: ../dashboard/kantor/maintenance.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $query . "<br>" . $koneksi->error;
        header("Location: ../dashboard/kantor/maintenance.php");
        exit();
    }

    $koneksi->close();
}
?>

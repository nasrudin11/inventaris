<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_peminjaman = $_POST["id_peminjaman"];
    $id_peminjaman_dtl = $_POST["id_peminjaman_dtl"];

    $query = "UPDATE peminjaman_dtl SET status = 'dikembalikan' WHERE id_peminjaman_dtl = '$id_peminjaman_dtl'";

    if ($koneksi->query($query) === TRUE) {
        $_SESSION['pesan'] = "Data barang berhasil diperbarui.";
        $koneksi->query($query2 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Edit the item')");

        header("Location: ../dashboard/kantor/peminjaman_dtl.php?id_peminjaman=$id_peminjaman");
        exit();
    } else {    
        $_SESSION['pesan'] = "Error: " . $query . "<br>" . $koneksi->error;

        header("Location: ../dashboard/kantor/peminjaman_dtl.php?id_peminjaman=$id_peminjaman");

        exit();
    }

    $koneksi->close();
}
?>

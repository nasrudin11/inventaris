<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pemesanan = $_POST["id_pemesanan"];

    $query = "UPDATE pemesanan SET status='dikonfirmasi' WHERE id_pemesanan='$id_pemesanan'";

    if ($koneksi->query($query) === TRUE) {
        $_SESSION['pesan'] = "Pesanan barang berhasil dikonfirmasi.";
        $koneksi->query($query2 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Confirm new order')");
        header("Location: ../dashboard/distributor/order_d.php");
        exit();
    } else {
        $_SESSION['pesan'] = "Error: " . $query . "<br>" . $koneksi->error;
        header("Location: ../dashboard/distributor/order_d.php");
        exit();
    }

    $koneksi->close();
}
?>

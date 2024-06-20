<?php
include 'koneksi.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_barang = $koneksi->real_escape_string($_POST["id_barang"]);
    $nama_barang = $koneksi->real_escape_string($_POST["nama_barang"]);
    $jumlah = $koneksi->real_escape_string($_POST["jumlah"]);

    // Validasi stok
    $query_check_stock = "SELECT stok FROM barang WHERE id_barang = '$id_barang'";
    $result = $koneksi->query($query_check_stock);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['stok'] < $jumlah) {
            $_SESSION['error_message'] = "Error: Jumlah barang yang dikeluarkan melebihi stok untuk barang $nama_barang.";
            header("Location: ../dashboard/kantor/barang_keluar.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Error: Barang dengan ID $id_barang tidak ditemukan.";
        header("Location: ../dashboard/kantor/barang_keluar.php");
        exit();
    }

    // Insert data ke tabel barang_keluar jika stok mencukupi
    $query = "INSERT INTO barang_keluar (id_barang, id_user, jumlah) VALUES ('$id_barang', '{$_SESSION['id']}', '$jumlah')";

    if ($koneksi->query($query) === TRUE) {
        $_SESSION['pesan'] = "Data barang berhasil dikeluarkan.";
        
        // Insert ke tabel riwayat
        $riwayat_text = $koneksi->real_escape_string("Adding a new item ($nama_barang)");
        $query2 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', '$riwayat_text')";
        if ($koneksi->query($query2) !== TRUE) {
            $_SESSION['error_message'] = "Error: " . $query2 . "<br>" . $koneksi->error;
            header("Location: ../dashboard/kantor/barang_keluar.php");
            exit();
        }

        header("Location: ../dashboard/kantor/barang_keluar.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $query . "<br>" . $koneksi->error;
        header("Location: ../dashboard/kantor/barang_keluar.php");
        exit();
    }

    $koneksi->close();
}
?>

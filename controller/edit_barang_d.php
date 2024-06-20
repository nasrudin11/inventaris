<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_barang = $_POST["id_barang"];
    $nama_barang = $_POST["nama_barang"];
    $kategori = $_POST["kategori"];
    $harga = $_POST["harga"];

    // Mendapatkan informasi file gambar yang diunggah
    $nama_file = $_FILES['gambar_barang']['name'];
    $ukuran_file = $_FILES['gambar_barang']['size'];
    $tmp_file = $_FILES['gambar_barang']['tmp_name'];
    $tipe_file = $_FILES['gambar_barang']['type'];

    // Direktori penyimpanan gambar
    $direktori = "../img/upload/barang/";

    // Query untuk mendapatkan data barang sebelumnya
    $query_get_barang = "SELECT * FROM barang WHERE id_barang = $id_barang";
    $result_get_barang = $koneksi->query($query_get_barang);

    if ($result_get_barang->num_rows > 0) {
        $row_barang = $result_get_barang->fetch_assoc();
        $gambar_barang_sebelumnya = $row_barang['gambar_barang'];

        // Jika input gambar baru tidak kosong, gunakan gambar baru
        if (!empty($nama_file)) {
            $nama_file_baru = uniqid() . '_' . $nama_file;
            if (move_uploaded_file($tmp_file, $direktori . $nama_file_baru)) {
                // Hapus gambar lama jika ada
                if (!empty($gambar_barang_sebelumnya)) {
                    unlink($direktori . $gambar_barang_sebelumnya);
                }
                $gambar_barang = $nama_file_baru;
            } else {
                $_SESSION['error_message'] = "Error uploading file.";
                header("Location: ../dashboard/disdistributor/tributor.php");
                exit();
            }
        } else {
            // Jika input gambar baru kosong, gunakan gambar sebelumnya
            $gambar_barang = $gambar_barang_sebelumnya;
        }

        // Query untuk mengupdate data barang
        $query_update_barang = "UPDATE barang SET nama_barang = '$nama_barang', kategori = '$kategori', harga = '$harga', gambar_barang = '$gambar_barang' WHERE id_barang = $id_barang";

        if ($koneksi->query($query_update_barang) === TRUE) {
            $_SESSION['pesan'] = "Data barang berhasil diperbarui.";
            $koneksi->query($query2 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Edit the item')");
            header("Location: ../dashboard/distributor/distributor.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error updating record: " . $koneksi->error;
            header("Location: ../dashboard/distributor/distributor.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Data barang tidak ditemukan.";
        header("Location: ../dashboard/distributor/distributor.php");
        exit();
    }
    $koneksi->close();
}

?>

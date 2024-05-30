<?php
include 'koneksi.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_peminjam = $_POST["nama_peminjam"];
    $tgl_peminjaman = $_POST["tgl_peminjaman"];
    $tgl_pengembalian = $_POST["tgl_pengembalian"];
    $id_barang = $_POST["id_barang"];
    $jumlah = $_POST['jumlah'];

    $query = "INSERT INTO peminjaman (id_user, nama_peminjam, tgl_peminjaman, tgl_pengembalian) VALUES ('{$_SESSION['id']}', '$nama_peminjam', '$tgl_peminjaman', '$tgl_pengembalian')";

    if ($koneksi->query($query) === TRUE) {
        $id_peminjaman = $koneksi->insert_id; // Ambil ID peminjaman yang baru saja dimasukkan
        
        $query2 = "INSERT INTO peminjaman_dtl (id_peminjaman, id_barang, jumlah) VALUES ('$id_peminjaman', '$id_barang', '$jumlah')";
        
        if ($koneksi->query($query2) === TRUE) {
            $koneksi->query("INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Adding a new loans in the name of ($nama_peminjam)')");
            $_SESSION['pesan'] = "Data barang berhasil ditambahkan.";
        } else {
            $_SESSION['error_message'] = "Error: " . $query2 . "<br>" . $koneksi->error;
        }
        
        header("Location: ../dashboard/kantor/peminjaman.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $query . "<br>" . $koneksi->error;
        header("Location: ../dashboard/kantor/peminjaman.php");
        exit();
    }

    $koneksi->close();
}
?>

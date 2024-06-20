<?php
include 'koneksi.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_barang = $koneksi->real_escape_string($_POST["id_barang"]);
    $jumlah = $koneksi->real_escape_string($_POST['jumlah']);

    // Validate stock
    $query_check_stock = "SELECT stok FROM barang WHERE id_barang = '$id_barang'";
    $result = $koneksi->query($query_check_stock);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['stok'] < $jumlah) {
            $_SESSION['error_message'] = "Error: Jumlah barang yang digunakan untuk maintenance melebihi stok yang tersedia.";
            header("Location: ../dashboard/kantor/maintenance.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Error: Barang dengan ID $id_barang tidak ditemukan.";
        header("Location: ../dashboard/kantor/maintenance.php");
        exit();
    }

    // Insert data into maintenance table if stock is sufficient
    $query = "INSERT INTO maintenance (id_user, id_barang, jumlah) VALUES ('{$_SESSION['id']}', '$id_barang', '$jumlah')";

    if ($koneksi->query($query) === TRUE) {
        // Update status to 'Maintenance'
        $query_update_status = "INSERT INTO status_barang (id_barang, nama_status) VALUES ('$id_barang', 'Maintenance') 
                                ON DUPLICATE KEY UPDATE nama_status = 'Maintenance'";
        if ($koneksi->query($query_update_status) !== TRUE) {
            $_SESSION['error_message'] = "Error: " . $query_update_status . "<br>" . $koneksi->error;
            header("Location: ../dashboard/kantor/maintenance.php");
            exit();
        }

        $_SESSION['pesan'] = "Data barang berhasil ditambahkan.";

        // Insert into riwayat table
        $riwayat_text = $koneksi->real_escape_string("Adding a new maintenance for item with ID $id_barang");
        $query2 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', '$riwayat_text')";
        if ($koneksi->query($query2) !== TRUE) {
            $_SESSION['error_message'] = "Error: " . $query2 . "<br>" . $koneksi->error;
            header("Location: ../dashboard/kantor/maintenance.php");
            exit();
        }

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

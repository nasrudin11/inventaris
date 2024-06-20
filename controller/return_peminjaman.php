<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_peminjaman = $koneksi->real_escape_string($_POST["id_peminjaman"]);
    $id_peminjaman_dtl = $koneksi->real_escape_string($_POST["id_peminjaman_dtl"]);
    $status_barang = $_POST["status"];

    // Retrieve id_barang from the peminjaman_dtl record
    $query_get_id_barang = "SELECT id_barang FROM peminjaman_dtl WHERE id_peminjaman_dtl = '$id_peminjaman_dtl'";
    $result = $koneksi->query($query_get_id_barang);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_barang = $row['id_barang'];

        // Check if the new status already exists for the item
        $query_check_status = "SELECT COUNT(*) AS count FROM status_barang WHERE id_barang = '$id_barang' AND nama_status = '$status_barang'";
        $result_check_status = $koneksi->query($query_check_status);
        $row_check_status = $result_check_status->fetch_assoc();
        
        if ($row_check_status['count'] == 0) {
            // Insert the new status for the item
            $query_insert_status = "INSERT INTO status_barang (id_barang, nama_status) VALUES ('$id_barang', '$status_barang')";
            if ($koneksi->query($query_insert_status) !== TRUE) {
                $_SESSION['error_message'] = "Error: " . $query_insert_status . "<br>" . $koneksi->error;
                header("Location: ../dashboard/kantor/peminjaman_dtl.php?id_peminjaman=$id_peminjaman");
                exit();
            }
        }

        // Update peminjaman_dtl record to 'dikembalikan'
        $query_update_peminjaman_dtl = "UPDATE peminjaman_dtl SET status = 'dikembalikan' WHERE id_peminjaman_dtl = '$id_peminjaman_dtl'";
        if ($koneksi->query($query_update_peminjaman_dtl) === TRUE) {
            // Insert into riwayat table
            $query_insert_riwayat = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Return the borrowed item with ID $id_barang and status $status_barang')";
            if ($koneksi->query($query_insert_riwayat) !== TRUE) {
                $_SESSION['error_message'] = "Error: " . $query_insert_riwayat . "<br>" . $koneksi->error;
                header("Location: ../dashboard/kantor/peminjaman_dtl.php?id_peminjaman=$id_peminjaman");
                exit();
            }

            // Check if there are other peminjaman_dtl records with the same id_barang and status 'Dipinjam'
            $query_check_other_peminjaman = "SELECT COUNT(*) AS count FROM peminjaman_dtl WHERE id_barang = '$id_barang' AND status = 'Dipinjam'";
            $result_check = $koneksi->query($query_check_other_peminjaman);
            $row_check = $result_check->fetch_assoc();
            
            if ($row_check['count'] == 0) {
                // If no other items with the same id_barang and status 'Dipinjam' exist, remove the 'Dipinjam' status from status_barang
                $query_delete_status = "DELETE FROM status_barang WHERE id_barang = '$id_barang' AND nama_status = 'Dipinjam'";
                if ($koneksi->query($query_delete_status) !== TRUE) {
                    $_SESSION['error_message'] = "Error: " . $query_delete_status . "<br>" . $koneksi->error;
                    header("Location: ../dashboard/kantor/peminjaman_dtl.php?id_peminjaman=$id_peminjaman");
                    exit();
                }
            }

            // Update status in peminjaman
            $query_update_peminjaman = "UPDATE peminjaman SET status = 'dikembalikan' WHERE id_peminjaman = '$id_peminjaman'";
            if ($koneksi->query($query_update_peminjaman) !== TRUE) {
                $_SESSION['error_message'] = "Error: " . $query_update_peminjaman . "<br>" . $koneksi->error;
                header("Location: ../dashboard/kantor/peminjaman_dtl.php?id_peminjaman=$id_peminjaman");
                exit();
            }

            $_SESSION['pesan'] = "Data barang berhasil dikembalikan.";
            header("Location: ../dashboard/kantor/peminjaman_dtl.php?id_peminjaman=$id_peminjaman");
            exit();
        } else {
            $_SESSION['error_message'] = "Error: " . $query_update_peminjaman_dtl . "<br>" . $koneksi->error;
            header("Location: ../dashboard/kantor/peminjaman_dtl.php?id_peminjaman=$id_peminjaman");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Error: Borrowed item record with ID $id_peminjaman_dtl not found.";
        header("Location: ../dashboard/kantor/peminjaman_dtl.php?id_peminjaman=$id_peminjaman");
        exit();
    }

    $koneksi->close();
}
?>

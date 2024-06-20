<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_maintenance = $koneksi->real_escape_string($_POST["id_maintenance"]);

    // Retrieve id_barang from the maintenance record
    $query_get_id_barang = "SELECT id_barang FROM maintenance WHERE id_maintenance = '$id_maintenance'";
    $result = $koneksi->query($query_get_id_barang);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_barang = $row['id_barang'];

        // Update maintenance record to 'dikembalikan'
        $query_update_maintenance = "UPDATE maintenance SET status_maintenance = 'dikembalikan' WHERE id_maintenance = '$id_maintenance'";
        if ($koneksi->query($query_update_maintenance) === TRUE) {
            
            // Check if there are other maintenance records with the same id_barang and status 'Maintenance'
            $query_check_other_maintenance = "SELECT COUNT(*) AS count FROM maintenance WHERE id_barang = '$id_barang' AND status_maintenance = 'Maintenance'";
            $result_check = $koneksi->query($query_check_other_maintenance);
            $row_check = $result_check->fetch_assoc();
            
            if ($row_check['count'] == 0) {
                // If no other items with the same id_barang and status 'Maintenance' exist, remove the 'Maintenance' status from status_barang
                $query_delete_status_maintenance = "DELETE FROM status_barang WHERE id_barang = '$id_barang' AND nama_status = 'Maintenance'";
                if ($koneksi->query($query_delete_status_maintenance) !== TRUE) {
                    $_SESSION['error_message'] = "Error: " . $query_delete_status_maintenance . "<br>" . $koneksi->error;
                    header("Location: ../dashboard/kantor/maintenance.php");
                    exit();
                }
            }

            // Check if there are other maintenance records with the same id_barang and status 'Rusak'
            $query_check_other_rusak = "SELECT COUNT(*) AS count FROM maintenance WHERE id_barang = '$id_barang' AND status_maintenance = 'Rusak'";
            $result_check_rusak = $koneksi->query($query_check_other_rusak);
            $row_check_rusak = $result_check_rusak->fetch_assoc();

            if ($row_check_rusak['count'] == 0) {
                // If no other items with the same id_barang and status 'Rusak' exist, remove the 'Rusak' status from status_barang
                $query_delete_status_rusak = "DELETE FROM status_barang WHERE id_barang = '$id_barang' AND nama_status = 'Rusak'";
                if ($koneksi->query($query_delete_status_rusak) !== TRUE) {
                    $_SESSION['error_message'] = "Error: " . $query_delete_status_rusak . "<br>" . $koneksi->error;
                    header("Location: ../dashboard/kantor/maintenance.php");
                    exit();
                }
            }

            // Insert into riwayat table
            $query_insert_riwayat = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Return the maintenance item with ID $id_barang')";
            if ($koneksi->query($query_insert_riwayat) !== TRUE) {
                $_SESSION['error_message'] = "Error: " . $query_insert_riwayat . "<br>" . $koneksi->error;
                header("Location: ../dashboard/kantor/maintenance.php");
                exit();
            }

            $_SESSION['pesan'] = "Data barang berhasil dikembalikan.";
            header("Location: ../dashboard/kantor/maintenance.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error: " . $query_update_maintenance . "<br>" . $koneksi->error;
            header("Location: ../dashboard/kantor/maintenance.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Error: Maintenance record with ID $id_maintenance not found.";
        header("Location: ../dashboard/kantor/maintenance.php");
        exit();
    }

    $koneksi->close();
}
?>

<?php
include 'koneksi.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_peminjam = $koneksi->real_escape_string($_POST["nama_peminjam"]);
    $tgl_peminjaman = $koneksi->real_escape_string($_POST["tgl_peminjaman"]);
    $tgl_pengembalian = $koneksi->real_escape_string($_POST["tgl_pengembalian"]);
    $id_barang = $_POST["id_barang"]; 
    $jumlah = $_POST["jumlah"]; 

    // Validate stock
    $is_stock_sufficient = true;
    foreach ($id_barang as $key => $id) {
        $id = $koneksi->real_escape_string($id);
        $jumlah_value = $koneksi->real_escape_string($jumlah[$key]);

        $query_check_stock = "SELECT stok FROM barang WHERE id_barang = '$id'";
        $result = $koneksi->query($query_check_stock);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['stok'] < $jumlah_value) {
                $is_stock_sufficient = false;
                $_SESSION['error_message'] = "Error: Jumlah barang yang digunakan untuk peminjaman melebihi stok yang tersedia.";
                header("Location: ../dashboard/kantor/peminjaman.php");
                exit();
            }
        } else {
            $is_stock_sufficient = false;
            $_SESSION['pesan'] = "Error: Barang dengan ID $id tidak ditemukan.";
            header("Location: ../dashboard/kantor/peminjaman.php");
            exit();
        }
    }

    if ($is_stock_sufficient) {
        // Insert data into peminjaman table
        $query = "INSERT INTO peminjaman (id_user, nama_peminjam, tgl_peminjaman, tgl_pengembalian) VALUES ('{$_SESSION['id']}', '$nama_peminjam', '$tgl_peminjaman', '$tgl_pengembalian')";

        if ($koneksi->query($query) === TRUE) {
            $id_peminjaman = $koneksi->insert_id;

            // Insert data into peminjaman_dtl table and update status
            foreach ($id_barang as $key => $id) {
                $id = $koneksi->real_escape_string($id);
                $jumlah_value = $koneksi->real_escape_string($jumlah[$key]);
                $query2 = "INSERT INTO peminjaman_dtl (id_peminjaman, id_barang, jumlah) VALUES ('$id_peminjaman', '$id', '$jumlah_value')";
                if ($koneksi->query($query2) !== TRUE) {
                    $_SESSION['error_message'] = "Error: " . $query2 . "<br>" . $koneksi->error;
                    header("Location: ../dashboard/kantor/peminjaman.php");
                    exit();
                }

                // Update status to 'Dipinjam'
                $query_update_status = "INSERT INTO status_barang (id_barang, nama_status) VALUES ('$id', 'Dipinjam') 
                                        ON DUPLICATE KEY UPDATE nama_status = 'Dipinjam'";
                if ($koneksi->query($query_update_status) !== TRUE) {
                    $_SESSION['error_message'] = "Error: " . $query_update_status . "<br>" . $koneksi->error;
                    header("Location: ../dashboard/kantor/peminjaman.php");
                    exit();
                }
            }

            // Insert into riwayat table
            $riwayat_text = $koneksi->real_escape_string("Adding a new loan in the name of ($nama_peminjam)");
            $query3 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', '$riwayat_text')";
            if ($koneksi->query($query3) !== TRUE) {
                $_SESSION['error_message'] = "Error: " . $query3 . "<br>" . $koneksi->error;
                header("Location: ../dashboard/kantor/peminjaman.php");
                exit();
            }

            $_SESSION['pesan'] = "Data barang berhasil ditambahkan.";
            header("Location: ../dashboard/kantor/peminjaman.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error: " . $query . "<br>" . $koneksi->error;
            header("Location: ../dashboard/kantor/peminjaman.php");
            exit();
        }
    }

    $koneksi->close();
}
?>

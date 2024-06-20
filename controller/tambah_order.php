<?php
include 'koneksi.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quantities = $_POST['quantities'];
    $id_barangs = $_POST['id_barang']; 
    $hargas = $_POST['harga']; 
    $id_distributors = $_POST['id_distributor'];

    // Insert data pemesanan 
    $query = "INSERT INTO pemesanan (id_user, id_user2) VALUES ({$_SESSION['id']}, $id_distributors)";
    if ($koneksi->query($query) === TRUE) {
        $id_pemesanan = $koneksi->insert_id;
    
        foreach ($id_barangs as $key => $id_barang) {
            $quantity = $quantities[$key]; 
            $harga_barang = $hargas[$key]; 

            if ($quantity > 0) {
                $query2 = "INSERT INTO pemesanan_dtl (id_pemesanan, id_barang, jumlah, harga) VALUES ($id_pemesanan, $id_barang, $quantity, $harga_barang)";
                if ($koneksi->query($query2) !== TRUE) {
                    $_SESSION['error_message'] = "Error: " . $query2 . "<br>" . $koneksi->error;
                    header("Location: ../dashboard/kantor/order_k_dtl.php");
                    exit();
                }
            }
        }

        // Insert into riwayat table
        $query3 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Menambahkan pesanan baru')";
        if ($koneksi->query($query3) !== TRUE) {
            $_SESSION['error_message'] = "Error: " . $query3 . "<br>" . $koneksi->error;
            header("Location: ../dashboard/kantor/order_k_dtl.php");
            exit();
        }

        $_SESSION['pesan'] = "Pemesanan data barang berhasil dilakukan";
        header("Location: ../dashboard/kantor/order_k_dtl.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $query . "<br>" . $koneksi->error;
        header("Location: ../dashboard/kantor/order_k_dtl.php");
        exit();
    }

    $koneksi->close();
}
?>

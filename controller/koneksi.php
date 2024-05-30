<?php
    $koneksi = new mysqli("localhost", "root", "", "inventaris_db");

    if ($koneksi->connect_error) {
        die("Koneksi Gagal: " . $koneksi->connect_error);
    }
    
?>

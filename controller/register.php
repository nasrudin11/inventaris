<?php
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    // Hash password sebelum disimpan ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk menambahkan data ke tabel user
    $query_user = "INSERT INTO user (email, password, role) VALUES ('$email', '$hashed_password', '$role')";

    if ($koneksi->query($query_user) === TRUE) {
        // Mendapatkan id_user yang baru saja dimasukkan
        $id_user = $koneksi->insert_id;

        // Query untuk menambahkan data ke tabel profil dengan menggunakan id_user yang baru saja dimasukkan
        $query_profil = "INSERT INTO profil (id_user, nama) VALUES ('$id_user', '$username')";

        if ($koneksi->query($query_profil) === TRUE) {
            // Menampilkan pesan sukses menggunakan modal Bootstrap
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var myModal = new bootstrap.Modal(document.getElementById('successModal'), {
                            keyboard: false
                        });
                        myModal.show();
                    });
                </script>";
        } else {
            echo "<div class='alert alert-danger'>Gagal menambahkan data ke tabel profil</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Registrasi Gagal</div>";
    }
    
    // Tutup koneksi
    $koneksi->close();
}
?>

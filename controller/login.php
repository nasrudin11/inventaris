<?php
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $queryUser = "SELECT * FROM user WHERE email = '$email'";
    $resultUser = $koneksi->query($queryUser);

    if ($resultUser->num_rows > 0) {
        $rowUser = $resultUser->fetch_assoc();
        if (isset($rowUser["password"])) {
            $hashed_password = $rowUser["password"];
            if (password_verify($password, $hashed_password)) {
                session_start();
                $role = $rowUser["role"];
                if ($role == "kantor") {
                    $queryName = "SELECT nama FROM profil WHERE id_user = '{$rowUser['id_user']}'";
                    $resultName = $koneksi->query($queryName);
                    if ($resultName && $resultName->num_rows > 0) {
                        $rowName = $resultName->fetch_assoc();
                        $_SESSION["nama"] = $rowName["nama"];
                    }

                    $_SESSION["role"] = "Inventaris Kantor";
                    $_SESSION["email"] = $email;
                    $_SESSION["id"] = $rowUser["id_user"];

                    $query = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'has been login')";
                    $koneksi->query($query);

                    header("Location: dashboard/kantor/kantor.php");
                    exit();
                } elseif ($role == "distributor") {
                    $queryName = "SELECT nama FROM profil WHERE id_user = '{$rowUser['id_user']}'";
                    $resultName = $koneksi->query($queryName);
                    if ($resultName && $resultName->num_rows > 0) {
                        $rowName = $resultName->fetch_assoc();
                        $_SESSION["nama"] = $rowName["nama"];
                    }

                    $_SESSION["role"] = "Distributor";
                    $_SESSION["email"] = $email;
                    $_SESSION["id"] = $rowUser["id_user"];

                    $query = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'has been login')";
                    $koneksi->query($query);

                    header("Location: dashboard/distributor/distributor.php");
                    exit();
                } else {
                    $error_message = "Peran tidak valid.";
                }
            } else {
                $error_message = "Kata sandi salah.";
            }
        }
    } else {
        $error_message = "User tidak ditemukan.";
    }

    $koneksi->close();
}
?>

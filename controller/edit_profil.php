<?php
include "koneksi.php";
session_start();

function editProfil($nama, $no_tlp, $alamat, $deskripsi, $id_user, $koneksi) {
    $query = "UPDATE profil SET nama = '$nama', no_tlp = '$no_tlp', alamat = '$alamat', deskripsi = '$deskripsi' WHERE id_user = $id_user";

    if ($koneksi->query($query) === TRUE) {
        $_SESSION['pesan'] = "Berhasil melakukan update profil.";
        $koneksi->query($query2 = "INSERT INTO riwayat (id_user, riwayat) VALUES ('{$_SESSION['id']}', 'Edit detail profile')");

        if($_SESSION['role'] == "Inventaris Kantor"){
            header("Location: ../dashboard/kantor/profil_k.php");
        }else{
            $_SESSION['pesan'] = "Berhasil melakukan update profil.";
            header("Location: ../dashboard/distributor/profil_d.php");
        }
        exit();
    } else {
    }
}


function editGambarProfil($gambar_profil, $id_user, $koneksi) {
    $folder = "../img/upload/profil/";
    $gambar_profil_name = $_FILES["gambar_profil"]["name"];
    $gambar_profil_tmp = $_FILES["gambar_profil"]["tmp_name"];

    if (move_uploaded_file($gambar_profil_tmp, "$folder/$gambar_profil_name")) {
        $query = "UPDATE profil SET gambar_profil = '$gambar_profil_name' WHERE id_user = '$id_user'";
        if ($koneksi->query($query) === TRUE) {
            $_SESSION['pesan'] = "Data foto profil berhasil diunggah.";
            if($_SESSION['role'] == "Inventaris Kantor"){
                header("Location: ../dashboard/kantor/profil_k.php");
            }else{
                $_SESSION['pesan'] = "Data foto profil berhasil diunggah.";
                header("Location: ../dashboard/distributor/profil_d.php");
            }
            exit();
        } else {
            echo "Error updating record: " . $koneksi->error;
        }
    } else {
        echo "Error uploading file.";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["edit_profil"])) {
        $nama = $_POST["nama"];
        $no_tlp = $_POST["no_tlp"];
        $alamat = $_POST["alamat"];
        $deskripsi = $_POST["deskripsi"];
        $id_user = $_SESSION["id"];

        editProfil($nama, $no_tlp, $alamat, $deskripsi, $id_user, $koneksi);
    }
    elseif (isset($_POST["edit_gambar_profil"])) {
        $id_user = $_SESSION["id"];

        editGambarProfil($_FILES["gambar_profil"], $id_user, $koneksi);
    }
}
?>

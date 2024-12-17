<?php
include "connect.php";

// Inisialisasi variabel $message
$message = "";

$judul = (isset($_POST['judul'])) ? htmlentities($_POST['judul']) : "";
$tanggal = (isset($_POST['tanggal'])) ? htmlentities($_POST['tanggal']) : "";
$isi = (isset($_POST['isi'])) ? htmlentities($_POST['isi']) : "";


$query = mysqli_query($conn, "INSERT INTO tb_pengumuman (judul,tanggal,isi) values ('$judul','$tanggal','$isi')");
if (!$query) {
    $message = '<script>alert("data gagal dimasukkan")</script>';
} else {
    $message = '<script>alert("data berhasil dimasukkan");
                window.location="../../pengumuman/pengumuman.php"</script>
                </script>';
}

// Pastikan variabel $message selalu terdefinisi sebelum dicetak
echo $message;

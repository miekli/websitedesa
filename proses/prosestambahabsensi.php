<?php
include "connect.php";

// Inisialisasi variabel $message
$message = "";

$tanggal = (isset($_POST['tanggal'])) ? htmlentities($_POST['tanggal']) : "";
$bulan = (isset($_POST['bulan'])) ? htmlentities($_POST['bulan']) : "";
$tahun = (isset($_POST['tahun'])) ? htmlentities($_POST['tahun']) : "";


$query = mysqli_query($conn, "INSERT INTO tb_tanggalabsen (tanggal,bulan,tahun) values ('$tanggal','$bulan','$tahun')");
if (!$query) {
    $message = '<script>alert("data gagal dimasukkan")</script>';
} else {
    $message = '<script>alert("data berhasil dimasukkan");
                window.location="../../absensi/absensi.php"</script>
                </script>';
}

// Pastikan variabel $message selalu terdefinisi sebelum dicetak
echo $message;

<?php
include "connect.php";

// Inisialisasi variabel $message
$message = "";

$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";

$query = mysqli_query($conn, "DELETE FROM tb_pengumuman WHERE id = '$id'");
if (!$query) {
    $message = '<script>alert("data gagal dimasukkan")</script>';
} else {
    $message = '<script>alert("data berhasil diunvalidasi");
                window.location="../../../pengumuman/pengumuman.php"</script>
                </script>';
}
// Pastikan variabel $message selalu terdefinisi sebelum dicetak
echo $message;
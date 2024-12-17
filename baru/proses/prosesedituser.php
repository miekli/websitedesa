<?php
include "connect.php";

// Inisialisasi pesan
$message = "";

// Mengambil data form dan melakukan sanitasi
$id = isset($_POST['id']) ? htmlentities($_POST['id']) : "";
$username = isset($_POST['username']) ? htmlentities($_POST['username']) : "";
$password = isset($_POST['password']) ? htmlentities($_POST['password']) : "";
$alamat = isset($_POST['alamat']) ? htmlentities($_POST['alamat']) : "";
$NIK = isset($_POST['NIK']) ? htmlentities($_POST['NIK']) : "";
$umur = isset($_POST['umur']) ? htmlentities($_POST['umur']) : "";

// Proses upload foto profil
$target_dir = "../assets/foto_profil/";
$profile = $_FILES['profile']['name'];
$imageType = strtolower(pathinfo($profile, PATHINFO_EXTENSION));
$maxFileSize = 2 * 1024 * 1024; // 2 MB
$allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];
$unique_file_name = uniqid() . '.' . $imageType;
$target_file = $target_dir . $unique_file_name;

if (!empty($profile)) {
    if (!in_array($imageType, $allowed_types)) {
        $message = '<script>alert("Hanya file JPG, JPEG, PNG, dan PDF yang diperbolehkan.");
                    window.location="../../absensi_perangkat/absensiperangkat.php"</script>';
    } elseif ($_FILES['profile']['size'] > $maxFileSize) {
        $message = '<script>alert("Ukuran file terlalu besar. Maksimal 2 MB.");
                    window.location="../../absensi_perangkat/absensiperangkat.php"</script>';
    } else {
        if (move_uploaded_file($_FILES['profile']['tmp_name'], $target_file)) {
            // Query update data dengan foto profil
            $query = "UPDATE tb_user SET 
                      username='$username',
                      password='$password',
                      alamat='$alamat',
                      NIK='$NIK',
                      umur='$umur',
                      profile='$unique_file_name'
                      WHERE id='$id'";
        } else {
            $message = '<script>alert("Gagal mengunggah file.");
                        window.location="../../absensi_perangkat/absensiperangkat.php"</script>';
        }
    }
} else {
    // Query update data tanpa mengubah foto profil
    $query = "UPDATE tb_user SET 
              username='$username',
              password='$password',
              alamat='$alamat',
              NIK='$NIK',
              umur='$umur'
              WHERE id='$id'";
}

// Eksekusi query
if (mysqli_query($conn, $query)) {
    $message = '<script>alert("Data berhasil diperbarui.");
                window.location="../../perangkat/perangkat.php"</script>';
} else {
    $message = '<script>alert("Gagal memperbarui data.");</script>';
}

// Menampilkan pesan
echo $message;
?>

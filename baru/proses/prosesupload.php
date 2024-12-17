<?php
include "connect.php";

// Initialize variables
$message = "";
$namaupload = (isset($_POST['namaupload'])) ? htmlentities($_POST['namaupload']) : "";
$keterangan = (isset($_POST['keterangan'])) ? htmlentities($_POST['keterangan']) : "";
$tanggalupload = (isset($_POST['tanggalupload'])) ? htmlentities($_POST['tanggalupload']) : "";

// File upload handling
$target_dir = "../assets/foto_upload/";
$original_file_name = basename($_FILES['upload']['name']);
$imageType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));
$maxFileSize = 2 * 1024 * 1024; // 1 MB in bytes

// Check if the file is a valid image or PDF
if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'pdf'])) {
    $message = '<script>alert("Hanya file JPG, JPEG, PNG, dan PDF yang diperbolehkan.");
                window.location="../../absensi_perangkat/absensiperangkat.php"</script>';
} elseif ($_FILES['upload']['size'] > $maxFileSize) {
    $message = '<script>alert("File terlalu besar. Maksimum ukuran file adalah 2 MB.");
                window.location="../../absensi_perangkat/absensiperangkat.php"</script>';
} else {
    // Create a unique filename
    $unique_file_name = uniqid() . '.' . $imageType;
    $target_file = $target_dir . $unique_file_name;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $target_file)) {
        // Prepare the query to insert data
        $query = mysqli_query($conn, "INSERT INTO tb_upload (namaupload, tanggalupload,keterangan, upload) VALUES ('$namaupload', '$tanggalupload','$keterangan', '$unique_file_name')");
        if (!$query) {
            $message = '<script>alert("Data gagal dimasukkan.")</script>';
        } else {
            $message = '<script>alert("Data berhasil dimasukkan.");
                        window.location="../../absensi_perangkat/absensiperangkat.php"</script>';
        }
    } else {
        $message = '<script>alert("Gagal memindahkan file yang diunggah.")</script>';
    }
}

// Ensure the $message variable is defined before printing
echo $message;
?>

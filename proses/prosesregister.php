<?php
include "connect.php";

// Initialize $message variable
$message = "";

// Retrieve form data and sanitize
$username = isset($_POST['username']) ? htmlentities($_POST['username']) : "";
$password = isset($_POST['password']) ? htmlentities($_POST['password']) : "";
$namalengkap = isset($_POST['namalengkap']) ? htmlentities($_POST['namalengkap']) : "";
$jenis = isset($_POST['jenis']) ? htmlentities($_POST['jenis']) : "";
$jabatan = isset($_POST['jabatan']) ? htmlentities($_POST['jabatan']) : "";
$alamat = isset($_POST['alamat']) ? htmlentities($_POST['alamat']) : "";
$NIK = isset($_POST['NIK']) ? htmlentities($_POST['NIK']) : "";
$umur = isset($_POST['umur']) ? htmlentities($_POST['umur']) : "";
$level = isset($_POST['level']) ? htmlentities($_POST['level']) : "";
$akses = isset($_POST['akses']) ? htmlentities($_POST['akses']) : "";

// Handle file upload
$target_dir = "../assets/foto_profil/";
$original_file_name = basename($_FILES['profile']['name']);
$imageType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));
$maxFileSize = 2 * 1024 * 1024; // 2 MB

// Check for valid file types and size
if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'pdf'])) {
    $message = '<script>alert("Only JPG, JPEG, PNG, and PDF files are allowed.");
                window.location="../../absensi_perangkat/absensiperangkat.php"</script>';
} elseif ($_FILES['profile']['size'] > $maxFileSize) {
    $message = '<script>alert("File too large. Maximum file size is 2 MB.");
                window.location="../../absensi_perangkat/absensiperangkat.php"</script>';
} else {
    // Create a unique filename for the uploaded file
    $unique_file_name = uniqid() . '.' . $imageType;
    $target_file = $target_dir . $unique_file_name;

    // Attempt to move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['profile']['tmp_name'], $target_file)) {

        // Check if the username already exists
        $select = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$username'");
        if (mysqli_num_rows($select) > 0) {
            $message = '<script>alert("Username already exists");</script>';
        } else {
            // Insert the data into the database
            $query = mysqli_query($conn, "INSERT INTO tb_user (username, password, namalengkap, jenis, jabatan, alamat, NIK, umur, profile, level, akses) VALUES ('$username', '$password', '$namalengkap', '$jenis', '$jabatan', '$alamat', '$NIK', '$umur', '$unique_file_name', '$level', '$akses')");
            
            if (!$query) {
                $message = '<script>alert("Data insertion failed");</script>';
            } else {
                $message = '<script>alert("Data successfully inserted");
                            window.location="../../perangkat/perangkat.php"</script>';
            }
        }
    } else {
        $message = '<script>alert("Failed to move the uploaded file.");</script>';
    }
}

// Ensure $message is output
echo $message;
?>

<?php
// File: auto_delete_expired_absensi.php
include "../proses/connect.php";

// Get today's date formatted as Y-m-d (e.g., 2024-11-14)
$today = date("Y-m-d");

// Delete records where the date is before today
$query_delete = "DELETE FROM tb_tanggalabsen 
                 WHERE STR_TO_DATE(CONCAT(tanggal, ' ', bulan, ' ', tahun), '%d %M %Y') < '$today'";

if (mysqli_query($conn, $query_delete)) {
    echo "Expired records deleted successfully.";
} else {
    echo "Error deleting records: " . mysqli_error($conn);
}
?>

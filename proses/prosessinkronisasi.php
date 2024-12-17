<?php
include "connect.php";

if (isset($_POST['update_user'])) {
    // Fetch all accounts from the source table
    $accounts = mysqli_query($conn, "SELECT * FROM tb_user"); // Ganti 'tb_user' dengan nama tabel yang sesuai

    // List of monthly tables
    $monthlyTables = ['tb_januari', 'tb_februari', 'tb_maret', 'tb_april', 'tb_mei', 'tb_juni', 
                      'tb_juli', 'tb_agustus', 'tb_september', 'tb_oktober', 'tb_november', 'tb_desember'];

    // Loop through each monthly table and delete all data before inserting new data
    foreach ($monthlyTables as $table) {
        // Delete all data in the monthly table
        $deleteQuery = "DELETE FROM $table"; 
        mysqli_query($conn, $deleteQuery);
    }

    // Loop through each account
    while ($row = mysqli_fetch_assoc($accounts)) {
        $namaLengkap = $row['namalengkap'];
        $jenis = ($row['jenis'] === 'pria') ? 'L' : 'P'; // Convert 'pria' to 'L' and 'wanita' to 'P'
        $jabatan = $row['jabatan'];

        // Loop through each monthly table and insert new data
        foreach ($monthlyTables as $table) {
            // Insert new data for each account
            $query = "INSERT INTO $table (namalengkap, jenis, jabatan)
                      VALUES ('$namaLengkap', '$jenis', '$jabatan')";

            mysqli_query($conn, $query);
        }
    }

    // Redirect or show success message
    header('Location: ../perangkat/perangkat.php'); // Adjust to the desired success page
    exit();
}
?>

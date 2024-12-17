<?php
include "../proses/connect.php"; // Ensure database connection

// Get data from the form
$id = $_POST['id'];
$tanggal = $_POST['tanggal'];
$keterangan = $_POST['keterangan'];

// Set the attendance status code
switch ($keterangan) {
    case "hadir":
        $keterangan = "H";
        break;
    case "cuti":
        $keterangan = "C";
        break;
    case "dinas":
        $keterangan = "D";
        break;
    case "sakit":
        $keterangan = "S";
        break;
    default:
        echo "Keterangan tidak valid.";
        exit();
}

// Define an array to map Indonesian month names to English month names
$indonesianMonths = [
    'Januari' => 'January',
    'Februari' => 'February',
    'Maret' => 'March',
    'April' => 'April',
    'Mei' => 'May',
    'Juni' => 'June',
    'Juli' => 'July',
    'Agustus' => 'August',
    'September' => 'September',
    'Oktober' => 'October',
    'November' => 'November',
    'Desember' => 'December'
];

// Replace Indonesian month with English month
foreach ($indonesianMonths as $indo => $eng) {
    $tanggal = str_replace($indo, $eng, $tanggal);
}

// Try to parse the date in the format "1 January 2025" (day-month-year with spaces)
$dateObject = DateTime::createFromFormat('j F Y', $tanggal);

// Check if the date parsing was successful
if ($dateObject) {
    // Format the date as 'Y-m-d' and extract year, month, and day
    $tanggal = $dateObject->format('Y-m-d');
    list($tahun, $bulan, $hari) = explode('-', $tanggal);
} else {
    echo "Format tanggal tidak valid.";
    exit();
}

// Determine the table based on the month
$nama_tabel = '';
switch ((int)$bulan) {
    case 1:
        $nama_tabel = 'tb_januari';
        break;
    case 2:
        $nama_tabel = 'tb_februari';
        break;
    case 3:
        $nama_tabel = 'tb_maret';
        break;
    case 4:
        $nama_tabel = 'tb_april';
        break;
    case 5:
        $nama_tabel = 'tb_mei';
        break;
    case 6:
        $nama_tabel = 'tb_juni';
        break;
    case 7:
        $nama_tabel = 'tb_juli';
        break;
    case 8:
        $nama_tabel = 'tb_agustus';
        break;
    case 9:
        $nama_tabel = 'tb_september';
        break;
    case 10:
        $nama_tabel = 'tb_oktober';
        break;
    case 11:
        $nama_tabel = 'tb_november';
        break;
    case 12:
        $nama_tabel = 'tb_desember';
        break;
    default:
        echo "Bulan tidak valid.";
        exit();
}

// Column corresponding to the day
$kolom_hari = 'day_' . (int)$hari;

// Fetch and display all names from the monthly table (e.g., tb_januari) for comparison
$query_all_names_monthly = "SELECT namalengkap FROM $nama_tabel";
$result_all_names_monthly = $conn->query($query_all_names_monthly);

if ($result_all_names_monthly->num_rows > 0) {
    $names_in_month_table = [];
    while ($row = $result_all_names_monthly->fetch_assoc()) {
        $names_in_month_table[] = $row['namalengkap'];  // Store names in an array
    }

    if (in_array($_POST['namalengkap'], $names_in_month_table)) {
        // Proceed to update the attendance record

        // Check if the user already exists in the monthly table
        $checkQuery = "SELECT * FROM $nama_tabel WHERE namalengkap = ?";
        $stmt_check = $conn->prepare($checkQuery);
        $stmt_check->bind_param("s", $_POST['namalengkap']);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            // Update the existing record for the specific day
            $updateQuery = "UPDATE $nama_tabel SET $kolom_hari = ? WHERE namalengkap = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ss", $keterangan, $_POST['namalengkap']);
            $updateStmt->execute();
            $updateStmt->close();
        } else {
            // Insert a new record if the user does not exist in the monthly table
            $insertQuery = "INSERT INTO $nama_tabel (namalengkap, $kolom_hari) VALUES (?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("ss", $_POST['namalengkap'], $keterangan);
            $insertStmt->execute();
            $insertStmt->close();
        }

        // Now delete the record from the tb_upload table after the attendance is validated
        $deleteQuery = "DELETE FROM tb_upload WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $id);  // Assuming the ID is used to identify the record in tb_upload
        $deleteStmt->execute();
        $deleteStmt->close();

        // JavaScript alert with redirect after OK
        echo "<script>
                alert('Absensi berhasil divalidasi dan data dihapus dari tb_upload!');
                window.location.href = '../absensi/verifikasi.php'; 
              </script>";
    } else {
        // Name not found in the monthly table
        echo "<script>
                alert('Nama yang dimasukkan tidak ditemukan di tabel bulan.');
                window.location.href = '../absensi/verifikasi.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Tidak ada nama di tabel bulanan $nama_tabel.');
            window.location.href = '../absensi/verifikasi.php';
          </script>";
}

$conn->close();
?>

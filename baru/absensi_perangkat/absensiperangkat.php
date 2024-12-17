<?php
session_start();
include "../proses/connect.php";

// Check if session is set
if (!isset($_SESSION['username_elabsen'])) {
    echo "Anda perlu login untuk mengakses halaman ini.";
    exit();
}

// Query to get user data
$query = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$_SESSION[username_elabsen]'");
if ($query) {
    $hasil = mysqli_fetch_array($query);
    if ($hasil) {
        $username = $hasil['username'];
        $jabatan = $hasil['jabatan'];
        $level = $hasil['level'];
        $namalengkap = $hasil['namalengkap'];
        $profile = $hasil['profile'];
    } else {
        echo "Data pengguna tidak ditemukan.";
        exit();
    }
} else {
    echo "Query gagal: " . mysqli_error($conn);
    exit();
}

// Mapping Indonesian month names to English
$indonesian_to_english_months = [
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

// Fetch all rows to process each one
$query_dates = mysqli_query($conn, "SELECT id, tanggal, bulan, tahun FROM tb_tanggalabsen");
if (!$query_dates) {
    echo "Error fetching dates: " . mysqli_error($conn);
    exit();
}

// Process each row individually
while ($row = mysqli_fetch_array($query_dates)) {
    // Convert Indonesian month to English
    $english_month = $indonesian_to_english_months[$row['bulan']] ?? null;

    if ($english_month) {
        // Construct the date string in the English format
        $date_string = $row['tanggal'] . ' ' . $english_month . ' ' . $row['tahun'];

        // Delete if the date is expired
        $query_delete_expired = mysqli_query(
            $conn,
            "DELETE FROM tb_tanggalabsen 
             WHERE id = {$row['id']} 
             AND STR_TO_DATE('$date_string', '%d %M %Y') < CURDATE()"
        );

        if (!$query_delete_expired) {
            echo "Error deleting expired dates for ID {$row['id']}: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid month format for entry ID {$row['id']}";
    }
}


// Query to get all uploads for the user
$query_uploads = mysqli_query($conn, "SELECT namaupload, tanggalupload FROM tb_upload WHERE namaupload = '$namalengkap'");
$uploads = [];
while ($upload = mysqli_fetch_array($query_uploads)) {
    $uploads[] = $upload; // Store all uploads in an array
}

// Query to get announcements
$query = mysqli_query($conn, "SELECT * FROM tb_tanggalabsen");
$result = [];
while ($record = mysqli_fetch_array($query)) {
    $result[] = $record;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="absensiperangkat.css">
    <style>
        .select-container {
            position: relative;
        }

        .select-container select {
            padding-right: 30px;
            /* Memberi ruang untuk ikon */
        }

        .select-container i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .avatar img {
            min-width: 60px;
            min-height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 30px;
        }

        .card-body p {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            height: auto;
            max-height: 70px;
            /* Menyesuaikan tinggi kartu */
        }

        .col-md-4 {
            max-width: 196px;
            /* Sesuaikan ukuran kartu agar lebih kecil */
        }

        .hidden-card {
            display: none;
        }

        .access-button.disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .hidden {
            display: none;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .no-announcement {
            color: white;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="logo">
            <img src="../assets/foto_home/logo.png" alt="Desa Sukanegara Logo">
            <h2>Desa Sukanegara</h2>
        </div>
        <ul class="menu">
            <a href="../home.php">
                <li class="menu-item"><i class="bi bi-house-door-fill"></i> Dashboard</li>
            </a>
            <a href="../profile.php">
                <li class="menu-item"><i class="bi bi-person-fill"></i> Profile</li>
            </a>
            <a href="../proses/proseslogout.php">
                <li class="menu-item1"><i class="bi bi-box-arrow-left"></i> Log out</li>
            </a>
        </ul>
    </div>

    <div class="content">
        <div class="header">
            <div class="hamburger" onclick="toggleSidebar()">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="user-info">
                <div class="avatar">
                    <img src="/assets/foto_profil/<?php echo $profile ?>" class="img-thumbnail" alt="...">
                </div>
                <div class="user-details">
                    <h3><?php echo $username ?></h3>
                    <p><?php echo $jabatan ?></p>
                </div>
            </div>
        </div>
        <div class="container-lg">
            <div class="d-flex justify-content-center align-items-start" style="min-height: 70vh; margin-top: 10px;">
                <div class="card mb-3" style="background-color: #8d8181; width: 100%; max-width: 1200px; height: auto;">
                    <div class="card-header text-white text-center" style="font-size: 0.5rem; font-weight: bold; margin-top:-40px">
                        <h2><b>Absensi Perangkat Desa Sukanegara</b></h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            if (empty($result)) {
                                echo "<div class='no-announcement text-center'>Tidak ada Presensi</div>";
                            } else {
                                foreach ($result as $row) {
                                    // Gabungkan tanggal, bulan, dan tahun menjadi satu string
                                    $tanggalAbsensi = $row['tanggal'] . ' ' . $row['bulan'] . ' ' . $row['tahun'];

                                    // Flag to determine if the user has uploaded for the current date
                                    $hasUpload = false;

                                    // Check if there's a matching upload for the current absensi
                                    foreach ($uploads as $upload) {
                                        $namaupload = $upload['namaupload'];
                                        $tanggalupload = $upload['tanggalupload'];

                                        // Check if the user uploaded for the specific date
                                        if ($namaupload == $namalengkap && $tanggalupload == $tanggalAbsensi) {
                                            $hasUpload = true; // Mark as having uploaded
                                            break; // Exit loop since we found a match
                                        }
                                    }

                                    // Only display if the user has not uploaded for the date
                                    if (!$hasUpload) {
                            ?>
                                        <div class="col-md-4 mb-4 absensi-card" data-date="<?php echo $tanggalAbsensi ?>">
                                            <div class="card h-100 d-flex flex-column" style="background-color: #ffffff; padding: 20px; position: relative;">
                                                <h5 class="card-title" style="font-weight: bold; border-bottom: 2px solid #ccc; padding-bottom: 8px; margin-bottom: 5px;">
                                                    <?php echo $tanggalAbsensi; ?>
                                                </h5>
                                                <p class="card-text" style="font-size: 0.85rem; color: #6c757d; margin-top: 0;">
                                                    absensi
                                                </p>
                                                <div style="position: absolute; bottom: 10px; right: 10px;">
                                                    <button class="btn btn-danger <?php if ($level != 1) { ?> hidden <?php } ?>" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row['id']; ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    <button class="btn btn-info btn-sn" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $row['id'] ?>">Access</button>
                                                </div>
                                            </div>
                                        </div>
                            <?php
                                    } // End of the if (!$hasUpload) check
                                } // End of foreach
                            } // End of else
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        foreach ($result as $row) {
        ?>
            <div class="modal fade" id="deleteModal<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Hapus Absensi Perangkat Desa</b></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="../../proses/prosesdeleteabsensi.php" method="POST">
                                <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                                Apakah Anda Ingin Menghapus Absensi pada tanggal <b><?php echo $row['tanggal'] ?> <?php echo $row['bulan'] ?> <?php echo $row['tahun'] ?>?</b>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger" name="input_user_validate" value="1234">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="viewModal<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Upload Absensi</b></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="../proses/prosesupload.php" class="form-container" method="POST" enctype="multipart/form-data">
                                <!-- Form presented as a table -->
                                <div class="form-table">

                                    <div class="form-row">
                                        <div class="form-label"><b>Nama Lengkap :</b></div>
                                        <div class="form-input">
                                            <input type="text" value="<?php echo $namalengkap ?>" class="form-control" id="floatingpassword" placeholder="Nama Lengkap Perangkat Desa" name="namaupload" required readonly>
                                        </div>
                                    </div>

                                    <div class="form-label"><b>Tanggal :</b></div>
                                    <div class="form-input">
                                        <input type="text" class="form-control" id="floatingInput" value="<?php echo $row['tanggal'] ?> <?php echo $row['bulan'] ?> <?php echo $row['tahun'] ?>" placeholder="Username Perangkat Desa" name="tanggalupload" required readonly>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-label"><b>Keterangan : </b></div>
                                        <div class="form-input">
                                            <div class="select-container">
                                                <select id="gender" class="form-control" name="keterangan">
                                                    <option value="hadir">Hadir</option>
                                                    <option value="sakit">Sakit</option>
                                                    <option value="cuti">Cuti</option>
                                                    <option value="dinas">Perjalanan Dinas</option>
                                                </select>
                                                <!-- Add a down arrow icon to indicate the dropdown can be clicked -->
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <div class="form-label"></div>
                                        <div class="form-input">
                                            <!-- Dinamis label akan berubah -->
                                            <label id="uploadLabel" for="input_upload"><b>Upload Foto Absensi:</b></label>
                                            <input class="form-control" id="input_upload" type="file" accept="image/png, image/jpeg, image/jpg, application/pdf" name="upload" required>
                                            <small class="text-muted">PNG, JPG, JPEG, PDF (Max 2 MB)</small>
                                        </div>
                                    </div>

                                    <!-- Submit button -->
                                    <div class="form-row mt-4">
                                        <div class="form-input">
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="sumbit" class="btn btn-success" name="input_user_validate" value="1234">Upload</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
    <script>
        // JavaScript untuk mengganti teks label berdasarkan pilihan dropdown
        document.querySelector("select[name='keterangan']").addEventListener("change", function() {
            const selectedValue = this.value; // Ambil nilai pilihan
            const uploadLabel = document.getElementById("uploadLabel"); // Referensi ke label

            // Ubah teks label sesuai dengan pilihan
            if (selectedValue === "hadir") {
                uploadLabel.textContent = "Upload Foto Absensi:";
            } else if (selectedValue === "sakit") {
                uploadLabel.textContent = "Upload Surat Keterangan Sakit:";
            } else if (selectedValue === "cuti") {
                uploadLabel.textContent = "Upload Surat Izin Cuti:";
            } else if (selectedValue === "dinas") {
                uploadLabel.textContent = "Upload Bukti Perjalanan Dinas:";
            } else {
                uploadLabel.textContent = "Upload:";
            }
        });
    </script>
</body>

</html>
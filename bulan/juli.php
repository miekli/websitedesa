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
        $profile = $hasil['profile'];
    } else {
        echo "Data pengguna tidak ditemukan.";
        exit();
    }
} else {
    echo "Query gagal: " . mysqli_error($conn);
    exit();
}

// Mengambil data dari tabel tb_juli
$query = mysqli_query($conn, "SELECT * FROM tb_juli");
$result = [];
while ($record = mysqli_fetch_array($query)) {
    $result[] = $record;
}
$year = date("Y");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="bulan.css">
    <style>
        .calendar-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .month-card {
            background-color: #8d8181;
            color: white;
            width: 450px;
            height: 600px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            text-align: center;
            font-size: 1.25rem;
            font-weight: bold;
        }

        .download-link {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 0.9rem;
            color: #00ffcc;
            text-decoration: none;
            font-weight: bold;
        }

        .month-name {
            margin-top: 15px;
            margin-bottom: 5px;
            border-bottom: 2px solid white;
            padding-bottom: 5px;
            width: 100%;
            text-align: center;
        }

        .title-underline {
            width: 100px;
            height: 2px;
            background-color: black;
            margin: 10px auto;
        }

        .avatar img {
            min-width: 60px;
            min-height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 30px;
        }

        #attendance-report {
            font-size: 14px;
            width: 210mm;
            padding: 20px;
            margin: 0 auto;
            box-sizing: border-box;
            visibility: hidden;
        }

        #attendance-report h2,
        #attendance-report h3 {
            text-align: center;
            font-weight: bold;
            margin: 0;
        }

        #attendance-report .subtitle {
            text-align: center;
            margin-top: 0;
            font-size: 14px;
            font-style: italic;
        }

        #attendance-report table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        #attendance-report th,
        #attendance-report td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            font-size: 12px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
        }

        .keterangan {
            font-size: 12px;
            width: 70%;
            padding-bottom: 20px;
        }

        .signature {
            font-size: 12px;
            text-align: right;
            width: 30%;
        }

        /* General layout styles */
        body {
            font-family: "Times New Roman", serif;
        }

        /* Centering and adjusting layout for portrait A4 */
        #attendance {
            font-size: 14px;
            width: 210mm;
            height: 297mm;
            border: 1px solid #000;
            padding: 20px;
            margin: 0 auto;
            line-height: 1.6;
            box-sizing: border-box;
        }

        #attendance h2 {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        #attendance table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        #attendance th,
        #attendance td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            font-size: 12px;
        }

        .signature {
            text-align: right;
            margin-top: 40px;
            margin-right: 50px;
        }

        /* Portrait printing settings */
        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            body * {
                visibility: hidden;
            }

            #attendance-report,
            #attendance-report * {
                visibility: visible;
            }

            #attendance-report {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            /* Layout Flexbox untuk footer saat print */
            .footer {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                width: 100%;
                margin-top: 20px;
            }

            .keterangan {
                font-size: 12px;
                width: 70%;
                /* Menentukan lebar keterangan supaya ada ruang untuk signature */
                padding-bottom: 20px;
            }

            .signature {
                font-size: 12px;
                text-align: right;
                width: 30%;
                /* Memberikan ruang untuk signature di sebelah kanan */
            }
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
                    <h3><?php echo $username ?>
                    </h3>
                    <p><?php echo $jabatan ?></p>
                </div>
            </div>
        </div>
        <div class="container">
            <h2 class="text-center text-black mt-5"><b>Unduh Absensi</b></h2>
            <div class="calendar-container">
                <div class="month-card">
                    <div class="month-name">Juli</div>
                    <img src="../assets/foto_download/image2.png" alt="Image Description" class="month-image">
                    <div class="modal-footer">
                        <button onclick="printStruck()" class="btn btn-info"><i class="bi bi-download"></i> Unduh Laporan</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Attendance Report Content (Hidden initially) -->
    <!-- Attendance Report Content -->
    <div id="attendance-report">
        <h2>DAFTAR HADIR BULANAN PERANGKAT DESA</h2>
        <h3>DESA SUKANEGARA, KECAMATAN TANJUNG BINTANG, KABUPATEN LAMPUNG SELATAN</h3>
        <p class="subtitle">Bulan: Juli ; Tahun: <?php echo $year; ?></p>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>L/P</th>
                    <?php for ($day = 1; $day <= 31; $day++): ?>
                        <th><?php echo $day; ?></th>
                    <?php endfor; ?>
                    <th>Ket</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($result as $row): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['namalengkap']; ?></td>
                        <td><?php echo $row['jabatan']; ?></td>
                        <td><?php echo $row['jenis']; ?></td>
                        <?php
                        for ($day = 1; $day <= 31; $day++):
                            $day_column = 'day_' . $day;
                            $absensi = $row[$day_column] ?? '';
                        ?>
                            <td><?php echo $absensi; ?></td>
                        <?php endfor; ?>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="footer">
            <div class="keterangan">
                <h4>Keterangan</h4>
                <p>L/P : Laki-laki/Perempuan</p>
                <p>H : Hadir</p>
                <p>C : Cuti</p>
                <p>S : Sakit</p>
                <p>D : Perjalanan Dinas</p>
            </div>
            <!-- Tanda tangan di sini -->
            <div class="signature">
                <p>Sukanegara, Juli <?php echo $year; ?></p>
                <p>Kepala Desa Sukanegara</p>
                <br><br><br>
                <p><strong>HERI TAMTOMO S.Sos</strong></p>
            </div>
        </div>
    </div>

    <script>
        function printStruck() {
            const attendanceContent = document.getElementById("attendance-report").innerHTML;
            const printWindow = window.open("", "_blank");
            printWindow.document.write(`
            <html>
                <head>
                    <title>Attendance Report</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                        }
                        h2 {
                            text-align: center;
                        }
                        h3 {
                            margin-top:-20px;
                            text-align: center;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 20px;
                            font-size: 12px;
                        }
                        th, td {
                            border: 1px solid black;
                            padding: 5px;
                            text-align: center;
                        }
                        .footer {
                            display: flex;
                            justify-content: space-between;
                            width: 100%;
                        }
                        .keterangan {
                            font-size: 12px;
                            width: 70%;
                        }
                        .signature {
                            font-size: 12px;
                            text-align: right;
                            width: 30%;
                        }
                    </style>
                </head>
                <body>
                    ${attendanceContent}
                </body>
            </html>
        `);
            printWindow.document.close();
            printWindow.print();
        }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>

</html>
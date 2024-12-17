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

// Query to get announcements
$query = mysqli_query($conn, "SELECT * FROM tb_pengumuman");
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="absensi.css">
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
            width: 230px;
            height: 230px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* Centers content vertically */
            align-items: center;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            text-align: center;
            font-size: 1.25rem;
            font-weight: bold;
            gap: 10px;
            margin-bottom: 10px;
            /* Adds spacing between elements */
        }

        .download-link {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 0.9rem;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .month-image {
            margin-top: 20px;
            margin-bottom: 40px;
            width: 100px;
            /* Adjusts the image size */
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
        <div class="container">
            <h2 class="text-center text-black mt-5"><b>Unduh Absensi</b></h2>
            <div class="title-underline"></div> <!-- Underline below title -->
            <div class="calendar-container">
                <!-- Each month card -->
                <div class="month-card">
                    <div class="month-name">Januari</div>
                    <img src="../assets/foto_download/image.png" alt="Image Description" class="month-image">
                    <a href="../../bulan/januari.php" class="download-link">Unduh</a>
                </div>


                <div class="month-card">
                    <div class="month-name">Februari</div>
                    <img src="../assets/foto_download/image.png" alt="Image Description" class="month-image">
                    <a href="../../bulan/Februari.php" class="download-link">Unduh</a>
                </div>

                <div class="month-card">
                    <div class="month-name">Maret</div>
                    <img src="../assets/foto_download/image.png" alt="Image Description" class="month-image">
                    <a href="../../bulan/maret.php" class="download-link">Unduh</a>
                </div>

                <div class="month-card">
                    <div class="month-name">April</div>
                    <img src="../assets/foto_download/image.png" alt="Image Description" class="month-image">
                    <a href="../../bulan/april.php" class="download-link">Unduh</a>
                </div>

                <div class="month-card">
                    <div class="month-name">Mei</div>
                    <img src="../assets/foto_download/image.png" alt="Image Description" class="month-image">
                    <a href="../../bulan/mei.php" class="download-link">Unduh</a>
                </div>

                <div class="month-card">
                    <div class="month-name">Juni</div>
                    <img src="../assets/foto_download/image.png" alt="Image Description" class="month-image">
                    <a href="../../bulan/juni.php" class="download-link">Unduh</a>
                </div>

                <div class="month-card">
                    <div class="month-name">Juli</div>
                    <img src="../assets/foto_download/image.png" alt="Image Description" class="month-image">
                    <a href="../../bulan/juli.php" class="download-link">Unduh</a>
                </div>

                <div class="month-card">
                    <div class="month-name">Agustus</div>
                    <img src="../assets/foto_download/image.png" alt="Image Description" class="month-image">
                    <a href="../../bulan/agustus.php" class="download-link">Unduh</a>
                </div>

                <div class="month-card">
                    <div class="month-name">September</div>
                    <img src="../assets/foto_download/image.png" alt="Image Description" class="month-image">
                    <a href="../../bulan/september.php" class="download-link">Unduh</a>
                </div>

                <div class="month-card">
                    <div class="month-name">Oktober</div>
                    <img src="../assets/foto_download/image.png" alt="Image Description" class="month-image">
                    <a href="../../bulan/oktober.php" class="download-link">Unduh</a>
                </div>

                <div class="month-card">
                    <div class="month-name">November</div>
                    <img src="../assets/foto_download/image.png" alt="Image Description" class="month-image">
                    <a href="../../bulan/november.php" class="download-link">Unduh</a>
                </div>

                <div class="month-card">
                    <div class="month-name">Desember</div>
                    <img src="../assets/foto_download/image.png" alt="Image Description" class="month-image">
                    <a href="../../bulan/desember.php" class="download-link">Unduh</a>
                </div>
            </div>
        </div>
    </div>

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
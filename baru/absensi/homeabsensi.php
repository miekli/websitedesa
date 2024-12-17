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
        .card-body p {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            height: auto;
            max-height: 100px;
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

        @media (min-width: 768px) {
            .col-md-4 {
                flex: 0 0 32%;
            }
        }

        .card {
            position: relative;
            /* Memungkinkan efek bayangan */
            overflow: hidden;
            /* Mencegah gambar melampaui batas kartu */
            transition: transform 0.3s ease;
            /* Transisi untuk efek 3D */
        }

        .judul {
            color: white;
            text-align: center;
            width: 100%;
            font-size: 75px;
            margin: 0;
            font-family: "Poppins", sans-serif;
        }

        .card img {
            transition: transform 0.3s ease;
            /* Transisi gambar */
            will-change: transform;
            /* Meningkatkan performa saat hover */
        }

        .card:hover {
            transform: translateY(-10px) rotateY(5deg);
            /* Mengangkat dan memutar kartu */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            /* Bayangan untuk efek 3D */
        }

        .card:hover img {
            transform: scale(1.05);
            /* Memperbesar gambar saat hover */
        }

        .full-height {
            height: 70vh;
            /* Adjust this value as needed */
            display: flex;
            justify-content: center;
            align-items: center;
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
        <div class="container-lg mt-5">
            <div class="row full-height">
                <div class="col-md-4 mb-4 col-sm-12 d-flex justify-content-center">
                    <a href="../absensi/absensi.php" class="card" style="cursor: pointer; background-color: #8d8181;">
                        <img src="../assets/foto_homeabsensi/image.png" class="card-img-top" alt="Gambar 1" style="height: 450px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title text-white"><b>Aktifkan Absensi</b></h5>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 mb-4 col-sm-12 d-flex justify-content-center">
                    <a href="../absensi/verifikasi.php" class="card" style="cursor: pointer; background-color: #8d8181;">
                        <img src="../assets/foto_homeabsensi/image2.png" class="card-img-top" alt="Gambar 2" style="height: 450px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title text-white"><b>Verifikasi Absensi</b></h5>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 mb-4 col-sm-12 d-flex justify-content-center">
                    <a href="../absensi/download.php" class="card" style="cursor: pointer; background-color: #8d8181;">
                        <img src="../assets/foto_homeabsensi/image3.png" class="card-img-top" alt="Gambar 3" style="height: 450px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title text-white"><b>Download Berkas Absensi</b></h5>
                        </div>
                    </a>
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
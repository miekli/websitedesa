<?php
session_start();
include "proses/connect.php";
// Check if session is set
if (!isset($_SESSION['username_elabsen'])) {
    echo "Anda perlu login untuk mengakses halaman ini.";
    exit();
}

// Menjalankan query untuk mendapatkan data pengguna
$query = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$_SESSION[username_elabsen]'");
if ($query) {
    $hasil = mysqli_fetch_array($query);

    // Pastikan data yang diambil valid
    if ($hasil) {
        $username = $hasil['username']; // Ganti dengan nama kolom yang sesuai
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
    <link rel="stylesheet" href="home.css">
    <style>
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
            <a href="#">
                <li class="menu-item active"><i class="bi bi-house-door-fill"></i> Dashboard</li>
            </a>
            <a href="../profile.php">
                <li class="menu-item"> <i class="bi bi-person-fill"></i> Profile</li>
            </a>
            <a href="../proses/proseslogout.php">
                <li class="menu-item1"><i class="bi bi-box-arrow-left"></i> Log out</li>
            </a>
        </ul>
    </div>

    <div class="content">
        <div class="header">
            <!-- Hamburger Menu -->
            <div class="hamburger" onclick="toggleSidebar()">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <!-- User Info -->
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


        <div class="dashboard">
            <div class="card">
                <a href="../absensi_perangkat/absensiperangkat.php">
                    <img src="../assets/foto_home/presensi.png" alt="Presensi Icon">
                    <h3><b>Presensi</b></h3>
                </a>
            </div>
            <div class="card">
                <a href="../pengumuman/pengumuman.php">
                    <img src="../assets/foto_home/pengumuman.png" alt="Pengumuman Icon">
                    <h3><b>Pengumuman</b></h3>
                </a>
            </div>
        </div>

        <?php if ($level == 1) { ?>
            <div class="dashboard">
                <div class="card">
                    <a href="../Perangkat/perangkat.php">
                        <img src="../assets/foto_home/perangkatdesa.png" alt="Presensi Icon">
                        <h3><b>Perangkat Desa</b></h3>
                    </a>
                </div>
                <div class="card">
                    <a href="../absensi/homeabsensi.php">
                        <img src="../assets/foto_home/validasi.png" alt="Pengumuman Icon">
                        <h3><b>Validasi Presensi</b></h3>
                    </a>
                </div>
            </div>
        <?php } ?>
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
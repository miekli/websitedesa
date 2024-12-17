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
        $username = $hasil['username'];
        $namalengkap = $hasil['namalengkap']; // Ganti dengan nama kolom yang sesuai
        $jenis = $hasil['jenis']; // Ganti dengan nama kolom yang sesuai
        $jabatan = $hasil['jabatan']; // Ganti dengan nama kolom yang sesuai
        $alamat = $hasil['alamat']; // Ganti dengan nama kolom yang sesuai
        $NIK = $hasil['NIK']; // Ganti dengan nama kolom yang sesuai
        $umur = $hasil['umur']; // Ganti dengan nama kolom yang sesuai
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
    <link rel="stylesheet" href="profile.css">
    <style>
        .avatar img {
            min-width: 60px;
            min-height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 30px;
        }

        /* Gaya untuk gambar profil besar dalam modal */
        .modal-avatar img {
            width: 200px;
            /* Ukuran untuk modal */
            height: 200px;
            border-radius: 50%;
            /* Membuat gambar bulat */
            object-fit: cover;
            /* Membuat gambar tetap proporsional */
            display: block;
            margin: 20px auto;
            background-color: #f0f0f0;
        }


        .edit-btn {
            display: block;
            margin: 10px auto;
            text-align: center;
            background-color: #6c757d;
            /* Gray color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .edit-btn:hover {
            background-color: #5a6268;
            /* Darker gray on hover */
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
            <a href="#">
                <li class="menu-item active"> <i class="bi bi-person-fill"></i> Profile</li>
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

        <div class="modal-body">
            <form>
                <div class="text-center">
                    <h3><B>Profile Anda</B></h3>
                    <div class="mb-3">
                        <div class="modal-avatar">
                            <img src="/assets/foto_profil/<?php echo $profile ?>" class="img-thumbnail" alt="...">
                        </div>
                    </div>
                </div>
                <!-- Nama -->
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" placeholder="Masukkan nama" value="<?php echo $namalengkap ?>" readonly>
                </div>

                <!-- Tempat dan Tanggal -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tempat" class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-control" id="tempat" placeholder="Masukkan tempat lahir" value="<?php echo $jenis ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal" class="form-label">Umur</label>
                        <input type="text" class="form-control" id="tanggal" value="<?php echo $umur ?>" readonly>
                    </div>
                </div>

                <!-- Status dan Pekerjaan -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="status" placeholder="Masukkan status" value="<?php echo $jabatan ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pekerjaan" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="pekerjaan" placeholder="Masukkan pekerjaan" value="<?php echo $NIK ?>" readonly>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat Domisili</label>
                    <input type="text" class="form-control" id="alamat" placeholder="Masukkan alamat" value="<?php echo $alamat ?>" readonly>
                </div>

            </form>
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
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
    <link rel="stylesheet" href="pengumuman.css">
    <style>
        .avatar img {
            min-width: 60px;
            min-height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 30px;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        .card-body p {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            height: auto;
            max-height: 90px;
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
                    <div class="card-header text-white text-center" style="font-size: 1.5rem; font-weight: bold; margin-top:-60px">
                        <h2><b>Pengumuman Desa Sukanegara</b></h2>
                    </div>
                    <div class="d-flex justify-content-end" style="padding: 10px;">
                        <?php if ($level == 1) { ?>
                            <button class="btn btn-success btn-sn" data-bs-toggle="modal" data-bs-target="#tambahpengumuman"><i class="bi bi-file-earmark-plus"></i> Tambah Pengumuman</button>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            if (empty($result)) {
                                echo "<div class='no-announcement text-center'>Tidak ada pengumuman</div>";
                            } else {
                                foreach ($result as $row) {
                            ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100 d-flex flex-column" style="background-color: #ffffff; padding: 20px; position: relative;">
                                            <!-- Judul di bagian paling atas dengan garis bawah -->
                                            <h5 class="card-title" style="font-weight: bold; border-bottom: 2px solid #ccc; padding-bottom: 8px; margin-bottom: 5px;">
                                                <?php echo $row['judul']; ?>
                                            </h5>
                                            <!-- Tanggal lebih kecil dan warna abu-abu di bawah judul -->
                                            <p class="card-text" style="font-size: 0.85rem; color: #6c757d; margin-top: 0;">
                                                <?php echo $row['tanggal']; ?>
                                            </p>
                                            <!-- Isi pengumuman yang panjang, terisi seluruhnya -->
                                            <p class="card-text" style="flex-grow: 1; margin-top: 10px;">
                                                <?php echo $row['isi']; ?>
                                            </p>
                                            <!-- Tombol Hapus di pojok kanan bawah -->
                                            <div style="position: absolute; bottom: 10px; right: 10px;">
                                                <button class="btn btn-info btn-sn" data-bs-toggle="modal" data-bs-target="#view<?php echo $row['id'] ?>"><i class="bi bi-megaphone-fill"></i> Details</button>
                                                <?php if ($level == 1) { ?>
                                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?php echo $row['id']; ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        foreach ($result as $row) {
        ?>
            <div class="modal fade" id="delete<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Hapus Pengumuman</b></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="../../proses/prosesdeletepengumuman.php" method="POST">
                                <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                                Apakah Anda Ingin Menghapus Pengumuman Berjudul <b><?php echo $row['judul'] ?>?</b>
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
            <div class="modal fade" id="view<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Detail Pengumuman Desa</b></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <!-- Judul -->
                                <div class="mb-3">
                                    <label for="judul" class="form-label"><b>Judul</b></label>
                                    <input type="text" class="form-control" id="judul" value="<?php echo $row['judul'] ?>" readonly>
                                </div>
                                <!-- Tanggal -->
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label"><b>Tanggal</b></label>
                                    <input type="text" class="form-control" id="tanggal" value="<?php echo $row['tanggal'] ?>" readonly>
                                </div>
                                <!-- Isi Pengumuman (Menggunakan textarea untuk teks panjang) -->
                                <div class="mb-3">
                                    <label for="isi" class="form-label"><b>Isi Pengumuman</b></label>
                                    <textarea class="form-control" id="isi" rows="8" readonly><?php echo $row['isi'] ?></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        <!-- Modal to add announcements -->
        <div class="modal fade" id="tambahpengumuman" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Tambah Pengumuman</b></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../proses/prosespengumuman.php" class="form-container" method="POST">
                            <div class="form-table">
                                <div class="form-label"><b>Judul :</b></div>
                                <div class="form-input">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="Judul Pengumuman" name="judul" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-label"><b>Tanggal :</b></div>
                                    <div class="form-input">
                                        <input type="date" class="form-control" id="floatingpassword" placeholder="Tanggal Pengumuman" name="tanggal" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label"><b>Isi :</b></div>
                                    <div class="form-input">
                                        <input type="text" class="form-control" id="floatingpassword" placeholder="Isi Pengumuman" name="isi" required>
                                    </div>
                                </div>
                                <div class="form-row mt-4">
                                    <div class="form-input">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success" name="input_user_validate" value="1234">Tambah</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
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
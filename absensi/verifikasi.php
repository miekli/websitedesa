<?php
session_start();
include "../proses/connect.php";
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
// Query to get announcements
$query = mysqli_query($conn, "SELECT * FROM tb_upload");
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
    <link rel="stylesheet" href="verifikasi.css">
    <style>
        .hidden {
            display: none;
        }

        .foto {
            display: flex;
            justify-content: center;
            /* Untuk mengahkan secara horizontal */
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
                <li class="menu-item"> <i class="bi bi-person-fill"></i> Profile</li>
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
        <div class="container-lg">
            <div class="d-flex justify-content-center align-items-start" style="min-height: 70vh; margin-top: 10px;">
                <div class="card mb-3" style="background-color: #8d8181; width: 100%; max-width: 1200px; height: auto;">
                    <div class="card-header text-white text-center" style="font-size: 1.5rem; font-weight: bold; margin-top:-60px">
                        <h2><b>Verifikasi Absensi</b></h2>
                    </div>
                    <div class="card-body table-responsive" style="overflow-x:auto;">
                        <table class="table table-hover table-striped table-bordered text-center" style="width :100%;">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama Lengkap</th>
                                    <th scope="col">Tanggal Absensi</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($result as $row) {
                                ?>
                                    <tr>
                                        <th scope="row"><?php echo $no++ ?></th>
                                        <td><?php echo $row['namaupload'] ?></td>
                                        <td><?php echo $row['tanggalupload'] ?></td>
                                        <td><?php echo $row['keterangan'] ?></td>
                                        <td>
                                            <button class="btn btn-success btn-sn" data-bs-toggle="modal" data-bs-target="#terima<?php echo $row['id'] ?>"><i class="bi bi-check2-circle"></i></button>
                                            <button class="btn btn-info btn-sn" data-bs-toggle="modal" data-bs-target="#view<?php echo $row['id'] ?>"><i class="bi bi-eye-fill"></i></button>
                                            <button class="btn btn-danger btn-sn" data-bs-toggle="modal" data-bs-target="#delete<?php echo $row['id'] ?>"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>

                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
        foreach ($result as $row) {
        ?>
            <!-- Modal -->
            <div class="modal fade" id="view<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Detail Absensi</b></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <!-- Nama -->
                                <div class="mb-3">
                                    <label for="nama" class="form-label"><b>Nama Lengkap :</b></label>
                                    <input type="text" class="form-control" id="nama" placeholder="Masukkan nama" value="<?php echo $row['namaupload'] ?> " readonly>
                                </div>

                                <!-- Nama -->
                                <div class="mb-3">
                                    <label for="nama" class="form-label"><b>Keterangan :</b></label>
                                    <input type="text" class="form-control" id="nama" placeholder="Masukkan nama" value="<?php echo $row['keterangan'] ?> " readonly>
                                </div>

                                <!-- Nama -->
                                <div class="mb-3">
                                    <label for="nama" class="form-label"><b>Tanggal Absensi :</b></label>
                                    <input type="text" class="form-control" id="nama" placeholder="Masukkan nama" value="<?php echo $row['tanggalupload'] ?> " readonly>
                                </div>
                                <!-- Nama -->
                                <div class="mb-3">
                                    <label for="nama" class="form-label"><b>Foto Upload :</b></label>
                                    <div class="foto">
                                        <img src="/assets/foto_upload/<?php echo $row['upload'] ?>" class="img-thumbnail" alt="...">
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="terima<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Terima Absensi</b></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="../../proses/prosesrekapbulan.php" method="POST">
                                <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                                <input type="hidden" value="<?php echo $row['tanggalupload'] ?>" name="tanggal">
                                <input type="hidden" value="<?php echo $row['keterangan'] ?>" name="keterangan">
                                <input type="hidden" name="namalengkap" value="<?php echo $row['namaupload']; ?>" required>
                                <div class="col-lg-12">
                                    Apakah Anda Ingin Menerima Absensi Sdr.<b><?php echo $row['namaupload'] ?>?</b>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" name="confirm_attendance" value="validate">Ya, Validasi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="delete<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Tolak Absensi</b></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="../../proses/prosesdeleteupload.php" method="POST">
                                <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                                <div class="col-lg-12">
                                    Apakah Anda Ingin Menolak Absensi Sdr.<b><?php echo $row['namaupload'] ?> ?</b>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="sumbit" class="btn btn-danger" name="input_user_validate" value="1234">Tolak</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!-- Modal -->

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
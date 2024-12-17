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
// Ambil semua data dari tabel tb_skck berdasarkan username dari session
$query = mysqli_query($conn, "SELECT * FROM tb_user");
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
    <link rel="stylesheet" href="perangkat.css">
    <style>
        .avatar img {
            min-width: 60px;
            min-height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 30px;
        }

        .hidden {
            display: none;
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
                    <div class="card-header text-white text-center" style="font-size: 1.5rem; font-weight: bold; margin-top:-40px">
                        <h2><b>Perangkat Desa Sukanegara</b></h2>
                    </div>
                    <div class="d-flex justify-content-end" style="padding: 5px;">
                        <button class="btn btn-danger btn-sn" style="margin : 5px" data-bs-toggle="modal" data-bs-target="#perhatian"><i class="bi bi-exclamation-triangle-fill"></i></button>
                        <button class="btn btn-info btn-sn" style="margin : 5px" data-bs-toggle="modal" data-bs-target="#sinkronisasi"><i class="bi bi-repeat"></i></button>
                        <button class="btn btn-success btn-sn" style="margin : 5px" data-bs-toggle="modal" data-bs-target="#tambahuser"><i class="bi bi-person-fill-add"></i> Tambah User</s></button>
                    </div>
                    <div class="card-body table-responsive" style="overflow-x:auto;">
                        <table class="table table-hover table-striped table-bordered text-center" style="width :100%;">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">username</th>
                                    <th scope="col">jabatan</th>
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
                                        <td><?php echo $row['username'] ?></td>
                                        <td><?php echo $row['jabatan'] ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sn" data-bs-toggle="modal" data-bs-target="#edit<?php echo $row['id'] ?>"><i class="bi bi-pen-fill"></i></button>
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
                            <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Detail Data Perangkat Desa</b></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <!-- Nama -->
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama" placeholder="Masukkan nama" value="<?php echo $row['namalengkap'] ?> " readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Password</label>
                                    <input type="text" class="form-control" id="nama" placeholder="Masukkan password" value="<?php echo $row['password'] ?> " readonly>
                                </div>
                                <!-- Tempat dan Tanggal -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tempat" class="form-label">Jenis</label>
                                        <input type="text" class="form-control" id="tempat" placeholder="Masukkan Jenis" value="<?php echo $row['jenis'] ?> " readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal" class="form-label">Jabatan</label>
                                        <input type="text" class="form-control" id="tanggal" value="<?php echo $row['jabatan'] ?> " readonly>
                                    </div>
                                </div>

                                <!-- Status dan Pekerjaan -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">NIK</label>
                                        <input type="text" class="form-control" id="status" placeholder="Masukkan status" value="<?php echo $row['NIK'] ?> " readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="pekerjaan" class="form-label">umur</label>
                                        <input type="text" class="form-control" id="pekerjaan" placeholder="Masukkan pekerjaan" value="<?php echo $row['umur'] ?> " readonly>
                                    </div>
                                    <!-- alamat -->
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Alamat Domisili</label>
                                        <input type="text" class="form-control" id="nama" placeholder="Masukkan nama" value="<?php echo $row['alamat'] ?> " readonly>
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
            <!-- Modal -->
            <div class="modal fade" id="delete<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Hapus Data Perangkat Desa</b></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="../../proses/prosesdelete.php" method="POST">
                                <p style="color: red; font-size: 10px !important;">
                                    <i>* Penghapusan, dan penambahan data perangkat hanya bisa dilakukan setelah bulan selesai (tidak ada absen) dan jangan lupa memencet tombol
                                        <button class="btn btn-info btn-sm" style="margin: 7px" disabled>
                                            <i class="bi bi-repeat"></i>
                                        </button> !!!
                                    </i>
                                </p>
                                <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                                <div class="col-lg-12">
                                    Apakah Anda Ingin Menghapus Data Perangkat Desa bernama <b><?php echo $row['username'] ?> ?</b>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="sumbit" class="btn btn-danger" name="input_user_validate" value="1234">Hapus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="edit<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Edit Perangkat Desa</b></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Keterangan -->
                            <form class="needs-validation" novalidate action="../proses/prosesedituser.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" value="<?php echo $row['id'] ?>" name="id">

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username'] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
                                </div>

                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $row['alamat'] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="umur" class="form-label">Umur</label>
                                    <input type="number" class="form-control" id="umur" name="umur" value="<?php echo $row['umur'] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="NIK" class="form-label">NIK</label>
                                    <input type="text" class="form-control" id="NIK" name="NIK" value="<?php echo $row['NIK'] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="profile" class="form-label">Upload Foto Profil</label>
                                    <input type="file" class="form-control" id="profile" name="profile" accept=".jpg,.jpeg,.png,.pdf">
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" name="update_user">Update Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!-- Modal -->
        <div class="modal fade" id="sinkronisasi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Sinkronisasi akun Perangkat Desa</b></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../proses/prosessinkronisasi.php" class="form-container" method="POST">
                            <p>Apakah Anda yakin ingin melakukan sinkronisasi untuk semua akun?</p>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success" name="update_user">Sinkronisasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="perhatian" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-fullscreen-md-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Informasi Penting !!!</b></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="col-lg-12">
                                <b>Tekan Tombol Sinkronisasi
                                    <button class="btn btn-info btn-sm" style="margin: 5px" disabled>
                                        <i class="bi bi-repeat"></i>
                                    </button>
                                    di Tiap anda menambah atau menghapus akun Serta di setiap <b>Pergantian Tahun</b> untuk menyesuaikan nama pada tiap tabel bulan!!!</b>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="tambahuser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Tambah akun Perangkat Desa</b></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../proses/prosesregister.php" class="form-container" method="POST" enctype="multipart/form-data">
                            <!-- Form presented as a table -->
                            <p style="color: red;"><i>* Penghapusan, dan penambahan data perangkat hanya bisa dilakukan setelah bulan selesai (tidak ada absen) dan jangan lupa memencet tombol <button class="btn btn-info btn-sm" style="margin: 5px" disabled>
                                        <i class="bi bi-repeat"></i>
                                    </button>!!!</i></p>
                            <div class="form-table">
                                <div class="form-labe">Username :</div>
                                <div class="form-input">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="Username Perangkat Desa" name="username" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">Password :</div>
                                    <div class="form-input">
                                        <input type="password" class="form-control" id="floatingpassword" placeholder="Password Perangkat Desa " name="password" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">Nama Lengkap :</div>
                                    <div class="form-input">
                                        <input type="text" class="form-control" id="floatingpassword" placeholder="Nama Lengkap Perangkat Desa" name="namalengkap" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">NIK :</div>
                                    <div class="form-input">
                                        <input type="text" class="form-control" id="floatingpassword" placeholder="NIK Perangkat Desa" name="NIK" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label">Umur :</div>
                                    <div class="form-input">
                                        <input type="text" class="form-control" id="floatingpassword" placeholder="Tanggal Lahir Perangkat Desa" name="umur" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-label">Jabatan :</div>
                                    <div class="form-input">
                                        <input type="text" class="form-control" id="floatingpassword" placeholder="Jabatan Perangkat Desa" name="jabatan" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-label">Jenis Kelamin:</div>
                                    <div class="form-input">
                                        <select id="gender" class="form-control" name="jenis">
                                            <option value="pria">Laki-laki</option>
                                            <option value="wanita">Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-label">Alamat :</div>
                                    <div class="form-input">
                                        <input type="text" class="form-control" id="floatingpassword" placeholder="Alamat Domisili Perangkat Desa" name="alamat" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-label"><b>Upload Foto Absensi :</b></div>
                                    <div class="form-input">
                                        <input class="form-control" id="input_ktp" type="file" accept="image/png, image/jpeg, image/jpg, application/pdf" name="profile" required>
                                        <small class="text-muted">PNG, JPG, JPEG, PDF (Max 1 MB)</small>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-label">Daftar Sebagai:</div>
                                    <div class="form-input">
                                        <select id="gender" class="form-control" name="level">
                                            <option value="1">Admin</option>
                                            <option value="2">Perangkat Desa</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-row readonly hidden">
                                    <div class="form-label"> :</div>
                                    <div class="form-input">
                                        <input type="text" class="form-control" value="1" id="floatingpassword" placeholder="Alamat Domisili Perangkat Desa" name="akses" required>
                                    </div>
                                </div>
                                <!-- Submit button -->
                                <!-- Submit button -->
                                <div class="form-row mt-4">
                                    <div class="form-input">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="sumbit" class="btn btn-success" name="input_user_validate" value="1234">Tambah</button>
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
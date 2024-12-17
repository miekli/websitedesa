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
        $akses = $hasil['akses'];
        $profile = $hasil['profile'];
    } else {
        echo "Data pengguna tidak ditemukan.";
        exit();
    }
} else {
    echo "Query gagal: " . mysqli_error($conn);
    exit();
}

$month = isset($_GET['month']) ? $_GET['month'] : date('n');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$daysOfWeek = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$totalDays = date('t', $firstDayOfMonth);
$startDay = date('w', $firstDayOfMonth);

$months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

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
            margin: 20px auto;
            max-width: 1300px;
            padding: 20px;
            border-radius: 10px;
            background-color: #e0e0e0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .calendar-header {
            background-color: #000;
            color: white;
            padding: 10px;
            border-radius: 10px 10px 0 0;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .custom-select {
            width: auto;
            padding: 8px;
            font-size: 1rem;
            color: #fff;
            background-color: #4B0082;
            border: 1px solid #4B0082;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .custom-select:hover {
            background-color: #6A0DAD;
        }

        .month-year-selector {
            display: flex;
            gap: 15px;
            align-items: center;
        }


        .separator {
            width: 100%;
            height: 2px;
            background-color: #ccc;
            margin-top: 10px;
        }

        .header-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }

        .calendar-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 50px;
        }

        .calendar-table th,
        .calendar-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .calendar-table th:first-child {
            color: red;
        }

        .day-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-weight: bold;
            color: white;
            background-color: #4B0082;
            border: none;
            display: inline-block;
            text-decoration: none;
            line-height: 40px;
        }

        .day-btn:hover {
            background-color: #6A0DAD;
        }

        select {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
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
                    <img src="../../assets/foto_profil/<?php echo $profile ?>" class="img-thumbnail" alt="...">
                </div>
                <div class="user-details">
                    <h3><?php echo $username ?></h3>
                    <p><?php echo $jabatan ?></p>
                </div>
            </div>
        </div>

        <div class="calendar-container">
            <div class="calendar-header">
                <h2><b>Absensi Perangkat Desa</b></h2>
            </div>
            <div class="separator"></div>
            <div class="header-nav">
                <form method="GET" class="month-year-selector">
                    <select name="month" onchange="this.form.submit()">
                        <?php
                        foreach ($months as $index => $monthName) {
                            $selected = ($index + 1 == $month) ? 'selected' : '';
                            echo "<option value='" . ($index + 1) . "' $selected>$monthName</option>";
                        }
                        ?>
                    </select>
                    <select name="year" onchange="this.form.submit()">
                        <?php
                        for ($y = date('Y') - 5; $y <= date('Y') + 5; $y++) {
                            $selected = ($y == $year) ? 'selected' : '';
                            echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                    </select>
                </form>
                <h2><b><?php echo $months[$month - 1] . " " . $year; ?></b></h2>
            </div>

            <table class="calendar-table">
                <thead>
                    <tr>
                        <?php foreach ($daysOfWeek as $day) : ?>
                            <th>
                                <h4><b><?php echo $day; ?></b></h4>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $day = 1;
                    for ($row = 0; $day <= $totalDays; $row++) {
                        echo "<tr>";
                        for ($col = 0; $col < 7; $col++) {
                            if (($row == 0 && $col < $startDay) || ($day > $totalDays)) {
                                echo "<td></td>";
                            } else {
                                $dateString = $day . " " . $months[$month - 1] . " " . $year;
                                echo "<td><button class='day-btn' onclick='openModal(\"$dateString\")'>$day</button></td>";
                                $day++;
                            }
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="bukaabsensi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-fullscreen-md-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><b>Buka Absensi Perangkat Desa</b></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" novalidate action="../../proses/prosestambahabsensi.php" method="POST">
                            <p>Apakah Anda Ingin Membuka Absensi pada tanggal <b id="selected-date"></b>?</p>
                            <input type="hidden" name="tanggal" value="">
                            <input type="hidden" name="bulan" value="">
                            <input type="hidden" name="tahun" value="">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success" name="input_user_validate" value="1234">Open</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
        <script>
            function openModal(dateString) {
                // Split dateString to get day, month, and year
                const [day, monthName, year] = dateString.split(" ");

                // Update the modal text
                document.getElementById('selected-date').textContent = dateString;

                // Set hidden inputs for day, month, and year
                document.querySelector('input[name="tanggal"]').value = day;
                document.querySelector('input[name="bulan"]').value = monthName;
                document.querySelector('input[name="tahun"]').value = year;

                // Show the modal
                var modal = new bootstrap.Modal(document.getElementById('bukaabsensi'));
                modal.show();
            }
        </script>
        <script>
            function toggleSidebar() {
                const sidebar = document.querySelector('.sidebar');
                sidebar.classList.toggle('open');
            }
        </script>
</body>

</html>
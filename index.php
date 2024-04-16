<?php
session_set_cookie_params(604800); // 604800 detik = 1 minggu
session_start();

// Data pengguna yang sah
$users = array(
    'admin' => 'admin'
);

function authenticate($username, $password) {
    global $users;
    return isset($users[$username]) && $users[$username] === $password;
}

// Logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// Redirect jika belum login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Proses form tamu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_masuk'])) {
        $nama = $_POST['nama'];
        tambahTamu($nama);
    } elseif (isset($_POST['submit_keluar'])) {
        $index = $_POST['index'];
        tandaiKeluar($index);
    } elseif (isset($_POST['submit_hapus'])) {
        $index = $_POST['index'];
        hapusTamu($index);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir Tamu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            text-align: center;
            position: relative;
            margin-top: 20px;
        }
        .logout-btn {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .tabel {
            border-collapse: collapse;
            width: 50%;
            margin: 20px auto;
        }
        .tabel th, .tabel td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }
        .tombol {
            background-color: #4CAF50;
            border: 0.5px;
            border-radius: 15px; 
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Daftar Tamu</h2>

    <!-- Tombol Logout -->
    <form method="post" class="logout-btn">
        <input type="submit" name="logout" value="Logout" class="tombol">
    </form>

    <!-- Form untuk tambah tamu -->
    <form method="post">
        <label for="nama">Nama Tamu:</label><br>
        <input type="text" id="nama" name="nama" required><br><br>
        <input type="submit" name="submit_masuk" value="Masuk" class="tombol">
    </form>

    <!-- Tabel daftar tamu -->
    <table class="tabel">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Tamu</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Keluar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['daftar_tamu'] as $index => $tamu): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo $tamu['nama']; ?></td>
                    <td><?php echo $tamu['tanggal_masuk']; ?></td>
                    <td><?php echo $tamu['tanggal_keluar'] ?? 'Belum keluar'; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <?php if (!$tamu['tanggal_keluar']): ?>
                                <input type="submit" name="submit_keluar" value="Keluar" class="tombol">
                            <?php endif; ?>
                            <input type="submit" name="submit_hapus" value="Hapus" class="tombol">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
